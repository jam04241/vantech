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

        DB::transaction(function () use ($product, $validated) {
            $price = $validated['price'];
            $productIds = Product::where('product_name', $product->product_name)
                ->where('brand_id', $product->brand_id)
                ->where('category_id', $product->category_id)
                ->pluck('id');

            foreach ($productIds as $productId) {
                $stock = Product_Stocks::firstOrNew(['product_id' => $productId]);
                if (!$stock->exists && $stock->stock_quantity === null) {
                    $stock->stock_quantity = 1;
                }
                $stock->price = $price;
                $stock->save();
            }
        });

        return back()->with('success', 'Price updated successfully.');
    }
}
