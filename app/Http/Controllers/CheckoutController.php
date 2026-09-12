<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerPurchaseOrder;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\Product_Stocks;
use App\Services\DRTransactionService;
use App\Traits\LogsAuditTrail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    use LogsAuditTrail;

    protected $drService;

    public function __construct(DRTransactionService $drService)
    {
        $this->drService = $drService;
    }
    public function store(Request $request)
    {
        Log::info('=== CHECKOUT PROCESS STARTED ===');
        Log::info('Request data:', $request->all());

        DB::beginTransaction();

        try {
            // Validate required fields
            $request->validate([
                // Optional: walk-in buyers often do not give a name.
                'customer_id' => 'nullable|exists:customers,id',
                'payment_method' => 'required|string|max:255',
                'bank_name' => 'nullable|string|max:255',
                'account_name' => 'nullable|string|max:255',
                'reference_no' => 'nullable|string|max:255',
                'amount' => 'required|numeric|min:0',
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.unit_price' => 'required|numeric|min:0',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.total_price' => 'required|numeric|min:0',
                'items.*.serial_number' => 'required|string',
            ]);

            $customerId = $request->customer_id;
            $paymentMethod = $request->payment_method;
            $bankName = $request->bank_name;
            $accountName = $request->account_name;
            $referenceNo = $request->reference_no;
            $amount = $request->amount;
            $items = $request->items;

            Log::info('Processing checkout for customer:', [
                'customer_id' => $customerId,
                'payment_method' => $paymentMethod,
                'bank_name' => $bankName,
                'account_name' => $accountName,
                'reference_no' => $referenceNo,
                'amount' => $amount,
                'items_count' => count($items)
            ]);

            // Step 1: Create DR Transaction first
            $totalSum = $request->total ?? $amount; // Get from line 87-88 equivalent (total after discount)
            $drTransaction = $this->drService->createDRTransaction('purchase', $totalSum);

            Log::info('DR Transaction created:', [
                'receipt_no' => $drTransaction->receipt_no,
                'total_sum' => $drTransaction->total_sum
            ]);

            // Step 2: Create customer purchase orders for each item and link to DR
            $purchaseOrderIds = [];
            foreach ($items as $index => $item) {
                Log::info("Creating purchase order for item {$index}:", $item);

                $purchaseOrder = CustomerPurchaseOrder::create([
                    'dr_receipt_id' => $drTransaction->id, // Link to DR transaction
                    'customer_id' => $customerId,
                    'product_id' => $item['product_id'],
                    'serial_number' => $item['serial_number'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['total_price'],
                    'order_date' => now()->format('Y-m-d'),
                    'status' => 'Success'
                ]);

                $purchaseOrderIds[] = $purchaseOrder->id;
                Log::info("Purchase order created with ID: {$purchaseOrder->id}");

                // Deduct the sold units from stock. Throws if the item is already
                // sold or short, which rolls the whole checkout back.
                $this->markProductAsSold(
                    $item['product_id'],
                    $item['serial_number'],
                    (int) $item['quantity']
                );
            }

            // Create payment method linked to the first purchase order
            if (!empty($purchaseOrderIds)) {
                Log::info('Creating payment method linked to purchase order:', [
                    'customer_purchase_order_id' => $purchaseOrderIds[0],
                    'method_name' => $paymentMethod,
                    'bank_name' => $bankName,
                    'account_name' => $accountName,
                    'reference_no' => $referenceNo,
                    'amount' => $amount
                ]);

                PaymentMethod::create([
                    'customer_purchase_order_id' => $purchaseOrderIds[0],
                    'method_name' => $paymentMethod,
                    'bank_name' => $bankName,
                    'account_name' => $accountName,
                    'reference_no' => $referenceNo,
                    'payment_date' => now()->format('Y-m-d'),
                    'amount' => $amount
                ]);

                Log::info('Payment method created successfully');
            }

            DB::commit();
            Log::info('=== CHECKOUT PROCESS COMPLETED SUCCESSFULLY ===');

            // May be null for a walk-in sale.
            $customer = $customerId ? Customer::find($customerId) : null;
            $totalQuantity = collect($items)->sum('quantity');
            $totalPrice = $amount;

            // Log the POS sale to audit trail
            $this->logSaleAudit('POS', $customer, $totalQuantity, $totalPrice, $request);

            // Store receipt data in session for receipt page
            $receiptData = [
                'drNumber' => $drTransaction->receipt_no, // Add DR number for barcode
                'customerName' => CustomerPurchaseOrder::customerLabel($customer),
                'customerId' => $customerId,
                'paymentMethod' => $paymentMethod,
                'bankName' => $bankName ?: 'N/A',
                'accountName' => $accountName ?: 'N/A',
                'referenceNo' => $referenceNo ?: 'N/A',
                'amount' => $amount,
                'subtotal' => $request->subtotal ?? 0,
                'discount' => $request->discount ?? 0,
                'total' => $amount,
                'items' => $this->getReceiptItemsData($items),
                'purchase_order_ids' => $purchaseOrderIds,
                'displayTotalOnly' => $request->displayTotalOnly === 'true' ? true : false
            ];

            session(['receiptData' => $receiptData]);

            // Return JSON response for SweetAlert
            return response()->json([
                'success' => true,
                'message' => 'Purchase completed successfully!',
                'redirect_url' => route('pos.purchasereceipt')
            ]);
        } catch (ValidationException $e) {
            // A rejected field is the caller's mistake, not a server fault.
            // Let Laravel return a 422 with per-field messages instead of
            // flattening it into a 500 labelled "Checkout failed".
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Checkout failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show purchase receipt
     */
    public function showReceipt()
    {
        $receiptData = session('receiptData');

        if (!$receiptData) {
            return redirect()->route('pos.itemlist')->with('error', 'No receipt data found. Please complete a purchase first.');
        }

        // Get customer contact info if available. A walk-in sale has no
        // customer id at all, so this stays 'N/A'.
        $customerContact = 'N/A';
        if (!empty($receiptData['customerId'])) {
            $customer = Customer::find($receiptData['customerId']);
            if ($customer) {
                $customerContact = $customer->contact_no ?: 'N/A';
            }
        }

        // Get authenticated user's full name and role
        $authenticatedUser = Auth::user();
        $preparedBy = 'N/A';
        $preparedByRole = 'N/A';
        if ($authenticatedUser) {
            $preparedBy = trim($authenticatedUser->first_name . ' ' .
                ($authenticatedUser->middle_name ? $authenticatedUser->middle_name . ' ' : '') .
                $authenticatedUser->last_name);

            // Translate role based on conditions
            if ($authenticatedUser->role === 'admin') {
                $preparedByRole = $authenticatedUser->id === 1 ? 'Owner' : 'Co Owner';
            } else {
                $preparedByRole = ucfirst($authenticatedUser->role ?? 'N/A');
            }
        }

        return view('POS_SYSTEM.PurchaseReceipt', compact('receiptData', 'customerContact', 'preparedBy', 'preparedByRole'));
    }

    /**
     * Deduct sold units from a product's stock.
     *
     * Previously this zeroed the stock row outright, so selling one unit wiped out
     * every remaining unit of that product. It also never checked availability,
     * which let the same serial be sold twice from two terminals at once.
     *
     * @throws \Exception when the serial does not match, or stock is insufficient.
     */
    private function markProductAsSold($productId, $serialNumber, int $quantity)
    {
        Log::info("Marking product as sold:", [
            'product_id' => $productId,
            'serial_number' => $serialNumber,
            'quantity' => $quantity,
        ]);

        $product = Product::find($productId);

        if (!$product) {
            throw new \Exception("Product #{$productId} no longer exists.");
        }

        // Guard against a cart line pointing at the wrong product: stock is tracked
        // per serial, so a mismatch would deduct from someone else's item.
        if ($serialNumber !== '' && $product->serial_number !== $serialNumber) {
            throw new \Exception(
                "Serial number {$serialNumber} does not belong to {$product->product_name}."
            );
        }

        // Lock the stock row so two concurrent checkouts cannot both pass the
        // availability check and oversell the same item.
        $stock = Product_Stocks::where('product_id', $productId)
            ->lockForUpdate()
            ->first();

        if (!$stock) {
            throw new \Exception("No stock record exists for {$product->product_name}.");
        }

        if ($stock->stock_quantity < $quantity) {
            throw new \Exception(
                "{$product->product_name} (SN: {$product->serial_number}) only has "
                . "{$stock->stock_quantity} left but {$quantity} were requested."
            );
        }

        $stock->stock_quantity -= $quantity;
        $stock->save();

        Log::info("Stock deducted", [
            'product_id' => $productId,
            'deducted' => $quantity,
            'remaining' => $stock->stock_quantity,
        ]);
    }

    /**
     * Get formatted items data for receipt
     */
    private function getReceiptItemsData($items)
    {
        $receiptItems = [];

        foreach ($items as $item) {
            $product = Product::find($item['product_id']);
            if ($product) {
                $receiptItems[] = [
                    'productName' => $product->product_name,
                    'price' => $item['unit_price'],
                    'warranty' => $product->warranty_label,
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['total_price'],
                    'serialNumber' => $item['serial_number']
                ];
            }
        }

        return $receiptItems;
    }
}
