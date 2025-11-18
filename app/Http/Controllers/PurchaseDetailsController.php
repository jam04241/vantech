<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseDetailsRequest;
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

    public function store(PurchaseDetailsRequest $request)
    {
        DB::transaction(function () use ($request) {
            $data = $request->validated();
            
            foreach ($data['items'] as $item) {
                // Ensure item_name exists (request rules already enforce this)
                if (empty($item['item_name'])) {
                    throw ValidationException::withMessages(['items' => 'Each item must include an item_name.']);
                }

                if ($item['item_type'] === 'bundle') {
                    // Handle bundle items - also update bundle quantity (only if selected)
                    $bundle = null;
                    if (!empty($item['item_id'])) {
                        $bundle = Bundles::find($item['item_id']);
                        if ($bundle) {
                            // Update the bundle's quantity_bundle field
                            $bundle->update([
                                'quantity_bundles' => $item['quantity'] // This updates the bundle table
                            ]);
                        }
                    }

                    $name = $item['item_name'] ?? ($bundle->bundle_name ?? null);

                    Purchase_Details::create([
                        'supplier_id' => $data['supplier_id'],
                        'bundle_id' => $item['item_id'] ?? null,
                        'product_id' => null,
                        'item_type' => $item['item_type'],
                        'item_name' => $name,
                        'quantity_ordered' => $item['quantity'], // This goes to purchase_details
                        'unit_price' => $item['unit_price'],
                        'total_price' => $item['total'],
                        'order_date' => $data['order_date'],
                        'status' => $data['status'],
                    ]);
                } else {
                    // Handle product items
                    $product = null;
                    if (!empty($item['item_id'])) {
                        $product = Product::find($item['item_id']);
                    }

                    $name = $item['item_name'] ?? ($product->product_name ?? null);

                    Purchase_Details::create([
                        'supplier_id' => $data['supplier_id'],
                        'product_id' => $item['item_id'] ?? null,
                        'bundle_id' => null,
                        'item_type' => $item['item_type'],
                        'item_name' => $name,
                        'quantity_ordered' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'total_price' => $item['total'],
                        'order_date' => $data['order_date'],
                        'status' => $data['status'],
                    ]);
                }
            }
        });

        return redirect()->route('suppliers.list')->with('success', 'Purchase order created successfully.');
    }
}