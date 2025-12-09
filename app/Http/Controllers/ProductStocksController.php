<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Product_Stocks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductStocksController extends Controller
{
    /**
     * Update price for a product (and all items sharing the same name).
     */
    public function updatePrice(Request $request, Product $product)
    {
        $validated = $request->validate([
            'price' => 'required|numeric|min:0',
        ]);

        $oldPrice = $product->stock?->price ?? 0;
        $newPrice = $validated['price'];
        $updatedCount = 0;

        DB::transaction(function () use ($product, $newPrice, &$updatedCount) {
            $productsToUpdate = Product::where('product_name', $product->product_name)
                ->where('brand_id', $product->brand_id)
                ->where('category_id', $product->category_id)
                ->where('product_condition', $product->product_condition)
                ->get();

            foreach ($productsToUpdate as $prod) {
                if ($prod->stock) {
                    $prod->stock->price = $newPrice;
                    $prod->stock->save();
                    $updatedCount++;
                }
            }
        });

        // Audit log
        $priceAction = $oldPrice > $newPrice ? 'Decrease' : 'Increase';
        $description = "{$priceAction} all price for {$product->product_name} = ₱{$oldPrice} => ₱{$newPrice}";
        if (method_exists($this, 'logUpdateAudit')) {
            $this->logUpdateAudit('UPDATE', 'Inventory', $description, ['price' => $oldPrice], ['price' => $newPrice], $request);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Price updated successfully.'
            ]);
        }
        return back()->with('success', 'Price updated successfully.');
    }
}
