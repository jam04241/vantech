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
        $products = Product::with('brand', 'category', 'supplier')->get();

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
        $products = Product::with('brand', 'category', 'supplier')->get();

        // Group by product_name and calculate total quantity
        // Each database entry with same product_name = 1 quantity, so we count them
        // If you have a quantity column, change this to: $quantity = $group->sum('quantity');
        $grouped = $products->groupBy('product_name')->map(function ($group) {
            $first = $group->first();
            
            // Count entries with same product_name (each entry = 1 unit)
            // If you add a quantity column later, change to: $quantity = $group->sum('quantity');
            $quantity = $group->count();
            
            return (object) [
                'id' => $first->id,
                'product_name' => $first->product_name,
                'brand' => $first->brand,
                'category' => $first->category,
                'supplier' => $first->supplier,
                'quantity' => $quantity,
                'price' => $first->price, // Use first product's price
                'serial_number' => $first->serial_number,
                'warranty_period' => $first->warranty_period,
                'image_path' => $first->image_path,
            ];
        })->values();

        return ['products' => $grouped];
    }
}
