<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase_Details;
use App\Models\Suppliers;
use App\Models\Bundles;
use App\Models\Product;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PurchaseDetailsController extends Controller
{
    public function create()
    {
        $suppliers = Suppliers::where('status', 'Active')->get();
        $bundles = Bundles::all();
        $products = Product::all();

        return view('SUPPLIERS.suppliers_purchase', compact('suppliers', 'bundles', 'products'));
    }

    public function index(Request $request)
    {
        // Start query
        $query = Purchase_Details::with(['supplier', 'bundle']);

        // Apply status filter if provided
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Apply date filter if provided
        if ($request->has('date') && $request->date != '') {
            $query->whereDate('order_date', $request->date);
        }

        // Apply search filter if provided
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                    ->orWhereHas('supplier', function ($q) use ($search) {
                        $q->where('company_name', 'LIKE', "%{$search}%");
                    })
                    ->orWhereHas('bundle', function ($q) use ($search) {
                        $q->where('bundle_name', 'LIKE', "%{$search}%");
                    });
            });
        }

        // Get paginated results with sorting
        $purchaseOrders = $query->orderBy('created_at', 'desc')->paginate(50);

        // Get statistics (unfiltered)
        $totalOrders = Purchase_Details::count();
        $pendingOrders = Purchase_Details::where('status', 'Pending')->count();
        $receivedOrders = Purchase_Details::where('status', 'Received')->count();
        $cancelledOrders = Purchase_Details::where('status', 'Cancelled')->count();

        return view('DASHBOARD.suppliers_orders', compact(
            'purchaseOrders',
            'totalOrders',
            'pendingOrders',
            'receivedOrders',
            'cancelledOrders'
        ));
    }

    public function store(Request $request)
    {
        // STEP 0: Check Authentication
        $user = Auth::user();

        if (!$user) {
            Log::warning('❌ AUTHENTICATION FAILED - User not authenticated');
            return back()->with('error', 'You must be logged in to create purchase orders.')->withInput();
        }

        Log::info('✅ AUTHENTICATION PASSED', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_role' => $user->role ?? 'unknown'
        ]);

        try {
            // STEP 1: Log incoming request with full details
            // Handle both array and string formats for items
            $itemsInfo = [];
            if (is_array($request->items)) {
                $itemsInfo = [
                    'items_type' => 'array',
                    'items_count' => count($request->items),
                    'items_preview' => array_slice($request->items, 0, 2) // Show first 2 items for preview
                ];
            } elseif (is_string($request->items)) {
                $itemsInfo = [
                    'items_type' => 'string',
                    'items_length' => strlen($request->items ?? ''),
                    'items_preview' => substr($request->items ?? '', 0, 100)
                ];
            } else {
                $itemsInfo = [
                    'items_type' => gettype($request->items),
                    'items_value' => $request->items
                ];
            }

            Log::info('=== PURCHASE ORDER SUBMISSION RECEIVED ===', array_merge([
                'user_id' => $user->id,
                'supplier_id' => $request->supplier_id,
                'order_date' => $request->order_date,
                'status' => $request->status,
                'request_all_keys' => array_keys($request->all())
            ], $itemsInfo));

            // STEP 2: Validate supplier_id
            if (!$request->supplier_id) {
                Log::error('❌ VALIDATION FAILED - Supplier ID is empty', [
                    'supplier_id' => $request->supplier_id
                ]);
                return back()->with('error', 'Supplier is required.')->withInput();
            }

            Log::info('✅ Supplier ID validation passed', ['supplier_id' => $request->supplier_id]);

            // STEP 3: Validate order_date
            if (!$request->order_date) {
                Log::error('❌ VALIDATION FAILED - Order date is empty', [
                    'order_date' => $request->order_date
                ]);
                return back()->with('error', 'Order date is required.')->withInput();
            }

            Log::info('✅ Order date validation passed', ['order_date' => $request->order_date]);

            // STEP 4: Validate items field exists and is not empty
            // Handle both array format (middleware-friendly) and JSON string format (backward compatibility)
            Log::info('📋 ITEMS FIELD CHECK', [
                'items_exists' => $request->has('items'),
                'items_type' => gettype($request->items),
                'items_is_array' => is_array($request->items),
                'items_is_string' => is_string($request->items),
            ]);

            if (!$request->has('items') || empty($request->items)) {
                Log::error('❌ VALIDATION FAILED - Items field is missing or empty', [
                    'has_items' => $request->has('items'),
                    'items_value' => $request->items,
                    'items_empty' => empty($request->items)
                ]);
                return back()->with('error', 'At least one item is required.')->withInput();
            }

            // STEP 5: Handle items - support both array format and JSON string format
            $items = null;

            if (is_array($request->items)) {
                // Array format (middleware-friendly): items[0][bundle_name], items[1][bundle_name], etc.
                // Laravel automatically converts form array notation to PHP array
                Log::info('📋 ITEMS RECEIVED AS ARRAY (Middleware-friendly format)', [
                    'items_count' => count($request->items),
                    'items_structure' => array_keys($request->items[0] ?? [])
                ]);
                $items = $request->items;
            } elseif (is_string($request->items)) {
                // JSON string format (backward compatibility)
                Log::info('📋 ITEMS RECEIVED AS JSON STRING (Decoding...)', [
                    'json_length' => strlen($request->items),
                    'json_preview' => substr($request->items, 0, 100)
                ]);

                $items = json_decode($request->items, true);
                $jsonError = json_last_error_msg();

                if (json_last_error() !== JSON_ERROR_NONE) {
                    Log::error('❌ JSON DECODE FAILED', [
                        'error' => $jsonError,
                        'raw_items' => $request->items
                    ]);
                    return back()->with('error', 'Invalid items format: ' . $jsonError)->withInput();
                }
            } else {
                Log::error('❌ INVALID ITEMS FORMAT', [
                    'items_type' => gettype($request->items),
                    'items_value' => $request->items
                ]);
                return back()->with('error', 'Items must be an array or valid JSON string.')->withInput();
            }

            // Validate items array
            if (!is_array($items) || empty($items)) {
                Log::error('❌ VALIDATION FAILED - Items is not an array or is empty', [
                    'is_array' => is_array($items),
                    'items_count' => is_array($items) ? count($items) : 0,
                    'items_type' => gettype($items)
                ]);
                return back()->with('error', 'Items must be a valid array with at least one item.')->withInput();
            }

            Log::info('✅ Items validation passed', [
                'items_count' => count($items),
                'format' => is_array($request->items) ? 'array' : 'json_string'
            ]);

            // STEP 6: Validate supplier exists
            $supplier = Suppliers::find($request->supplier_id);
            if (!$supplier) {
                Log::error('❌ SUPPLIER NOT FOUND', [
                    'supplier_id' => $request->supplier_id
                ]);
                return back()->with('error', 'Selected supplier does not exist.')->withInput();
            }

            Log::info('✅ Supplier found', ['supplier_id' => $supplier->id, 'supplier_name' => $supplier->supplier_name]);

            // STEP 7: Validate order date format
            try {
                $orderDate = Carbon::parse($request->order_date);
                Log::info('✅ Order date parsed successfully', ['order_date' => $orderDate->format('Y-m-d')]);
            } catch (\Exception $e) {
                Log::error('❌ ORDER DATE PARSE FAILED', [
                    'error' => $e->getMessage(),
                    'order_date' => $request->order_date
                ]);
                return back()->with('error', 'Invalid order date format.')->withInput();
            }

            // STEP 8: Validate status
            $status = $request->status ?? 'Pending';
            if (!in_array($status, ['Pending', 'Received', 'Cancelled'])) {
                $status = 'Pending';
            }

            Log::info('✅ Status validated', ['status' => $status]);

            // STEP 9: Begin transaction
            DB::beginTransaction();
            Log::info('✅ Database transaction started');

            // STEP 10: Process each item
            foreach ($items as $index => $item) {
                Log::info("📦 PROCESSING ITEM " . ($index + 1) . "/" . count($items), $item);

                // Validate item fields
                if (empty($item['bundle_name'])) {
                    throw new \Exception("Item " . ($index + 1) . ": Bundle name is required.");
                }
                if (empty($item['bundle_type'])) {
                    throw new \Exception("Item " . ($index + 1) . ": Bundle type is required.");
                }
                if (empty($item['quantity_bundle']) || $item['quantity_bundle'] < 1) {
                    throw new \Exception("Item " . ($index + 1) . ": Quantity bundle must be at least 1.");
                }
                if (empty($item['quantity_ordered']) || $item['quantity_ordered'] < 1) {
                    throw new \Exception("Item " . ($index + 1) . ": Quantity ordered must be at least 1.");
                }
                if (empty($item['unit_price']) || $item['unit_price'] < 0) {
                    throw new \Exception("Item " . ($index + 1) . ": Unit price must be 0 or greater.");
                }

                Log::info("✅ Item " . ($index + 1) . " validation passed");

                // Create or update bundle
                $bundle = Bundles::updateOrCreate(
                    [
                        'bundle_name' => $item['bundle_name'],
                        'bundle_type' => ucfirst($item['bundle_type'])
                    ],
                    [
                        'quantity_bundles' => (int)$item['quantity_bundle']
                    ]
                );

                Log::info("✅ Bundle created/updated for item " . ($index + 1), ['bundle_id' => $bundle->id]);

                // Calculate total price
                $totalPrice = (float)$item['quantity_ordered'] * (float)$item['unit_price'];

                // Create purchase detail record
                $purchaseDetail = Purchase_Details::create([
                    'supplier_id' => $supplier->id,
                    'bundle_id' => $bundle->id,
                    'quantity_ordered' => (int)$item['quantity_ordered'],
                    'unit_price' => (float)$item['unit_price'],
                    'total_price' => $totalPrice,
                    'order_date' => $orderDate->format('Y-m-d'),
                    'status' => $status
                ]);

                Log::info("✅ Purchase detail created for item " . ($index + 1), [
                    'purchase_detail_id' => $purchaseDetail->id,
                    'total_price' => $totalPrice
                ]);
            }

            // STEP 11: Commit transaction
            DB::commit();
            Log::info('✅ Database transaction committed');

            Log::info('=== ✅ PURCHASE ORDER CREATED SUCCESSFULLY ===', [
                'user_id' => $user->id,
                'supplier_id' => $supplier->id,
                'items_count' => count($items),
                'status' => $status,
                'total_value' => array_sum(array_map(function ($item) {
                    return (float)$item['quantity_ordered'] * (float)$item['unit_price'];
                }, $items))
            ]);

            return redirect()->route('suppliers.list')->with('success', 'Purchase order created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ PURCHASE ORDER CREATION FAILED', [
                'user_id' => $user->id ?? 'unknown',
                'error_message' => $e->getMessage(),
                'error_line' => $e->getLine(),
                'error_file' => $e->getFile(),
                'error_trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Failed to create purchase order: ' . $e->getMessage())->withInput();
        }
    }

    public function confirm($id)
    {
        try {
            DB::beginTransaction();

            $order = Purchase_Details::findOrFail($id);

            // Update order status to Received
            $order->update([
                'status' => 'Received'
            ]);

            DB::commit();

            // Get updated statistics
            $statistics = $this->getOrderStatistics();

            return response()->json([
                'success' => true,
                'message' => 'Order marked as received successfully.',
                'order' => $order,
                'statistics' => $statistics
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to update order: ' . $e->getMessage()
            ], 500);
        }
    }

    public function cancel($id)
    {
        try {
            DB::beginTransaction();

            $order = Purchase_Details::findOrFail($id);

            // Update order status to Cancelled
            $order->update([
                'status' => 'Cancelled'
            ]);

            DB::commit();

            // Get updated statistics
            $statistics = $this->getOrderStatistics();

            return response()->json([
                'success' => true,
                'message' => 'Order cancelled successfully.',
                'order' => $order,
                'statistics' => $statistics
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel order: ' . $e->getMessage()
            ], 500);
        }
    }

    public function statistics()
    {
        try {
            $statistics = $this->getOrderStatistics();

            return response()->json([
                'success' => true,
                ...$statistics
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch statistics: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getOrderStatistics()
    {
        return [
            'totalOrders' => Purchase_Details::count(),
            'pendingOrders' => Purchase_Details::where('status', 'Pending')->count(),
            'receivedOrders' => Purchase_Details::where('status', 'Received')->count(),
            'cancelledOrders' => Purchase_Details::where('status', 'Cancelled')->count(),
        ];
    }
}
