<?php

namespace App\Traits;

use App\Models\Product;

trait LoadsProductData
{
    /**
     * Retrieve products with their related brand, category, and supplier data.
     *
     * @return array{products: \Illuminate\Support\Collection}
     */
    protected function loadProducts(): array
    {
        $products = Product::with('brand', 'category', 'supplier', 'stock')->get();

        return compact('products');
    }

    /**
     * Retrieve products grouped by product_name with summed quantities.
     * Products with the same name will be combined into one row with total quantity.
     *
     * @return array{products: \Illuminate\Support\Collection}
     */
    protected function loadGroupedProducts(): array
    {
        $products = Product::with('brand', 'category', 'supplier', 'stock')->get();

        $projected = $products->map(function ($product) {
            return (object) [
                'id' => $product->id,
                'product_name' => $product->product_name,
                'brand' => $product->brand,
                'category' => $product->category,
                'supplier' => $product->supplier,
                'quantity' => $product->stock?->stock_quantity ?? 0,
                'price' => $product->stock?->price ?? 0,
                'serial_number' => $product->serial_number,
                'warranty_period' => $product->warranty_period,
                'image_path' => $product->image_path ?? null,
            ];
        });

        return ['products' => $projected];
    }
}
