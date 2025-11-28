<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase_Details;
use App\Models\Suppliers;
use App\Models\Bundles;
use App\Models\Product;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

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
            $query->where(function($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                  ->orWhereHas('supplier', function($q) use ($search) {
                      $q->where('company_name', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('bundle', function($q) use ($search) {
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
        try {
            // Validate the request
            $validated = $request->validate([
                'supplier_id' => 'required|exists:suppliers,id',
                'order_date' => 'required|date',
                'status' => 'required|in:Pending,Received,Cancelled',
                'items' => 'required|json'
            ]);

            DB::beginTransaction();

            // Decode the items JSON
            $items = json_decode($request->items, true);
            
            if (empty($items)) {
                throw ValidationException::withMessages([
                    'items' => ['At least one item is required.']
                ]);
            }

            foreach ($items as $item) {
                // Validate item data
                $itemValidated = validator($item, [
                    'bundle_name' => 'required|string|max:255',
                    'bundle_type' => 'required|in:product,pack,bundle',
                    'quantity_bundle' => 'required|integer|min:1',
                    'quantity_ordered' => 'required|integer|min:1',
                    'unit_price' => 'required|numeric|min:0',
                ])->validate();

                // Create or update bundle
                $bundle = Bundles::updateOrCreate(
                    [
                        'bundle_name' => $itemValidated['bundle_name'],
                        'bundle_type' => ucfirst($itemValidated['bundle_type'])
                    ],
                    [
                        'quantity_bundles' => $itemValidated['quantity_bundle']
                    ]
                );

                // Calculate total price
                $totalPrice = $itemValidated['quantity_ordered'] * $itemValidated['unit_price'];

                // Create purchase detail record
                Purchase_Details::create([
                    'supplier_id' => $validated['supplier_id'],
                    'bundle_id' => $bundle->id,
                    'quantity_ordered' => $itemValidated['quantity_ordered'],
                    'unit_price' => $itemValidated['unit_price'],
                    'total_price' => $totalPrice,
                    'order_date' => $validated['order_date'],
                    'status' => $validated['status']
                ]);
            }

            DB::commit();

            return redirect()->route('suppliers.list')->with('success', 'Purchase order created successfully.');

        } catch (ValidationException $e) {
            DB::rollBack();
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create purchase order: ' . $e->getMessage());
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