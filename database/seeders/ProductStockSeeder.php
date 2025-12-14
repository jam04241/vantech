<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $productStocks = [];

        // Define realistic prices for each product type in pesos
        $productPrices = [
            1 => 25000,    // AMD Ryzen 7 7800X3D
            2 => 22000,    // ASUS Dual GeForce RTX 4060
            3 => 35000,    // Samsung 990 Pro 4TB SSD
            4 => 18000,    // Asus ROG LOKI 1200W PSU
            5 => 2800,     // Dark Flash WD200 PC Case
            6 => 12000,    // MSI G244F Monitor
            7 => 4500,     // Thermalright Frozen Warframe 360
            8 => 11000,    // MSI B650M GAMING PLUS
            9 => 4800,     // Corsair Vengeance RGB DDR5 16GB
            10 => 13000,   // Epson L3150 Printer
        ];

        // Loop through all 5000 products
        for ($productId = 1; $productId <= 5000; $productId++) {
            // Determine which product type this belongs to (1-10)
            // Since we created 500 of each type in sequence:
            // Product IDs 1-500: Type 1
            // Product IDs 501-1000: Type 2
            // Product IDs 1001-1500: Type 3
            // ... and so on
            $productType = ceil($productId / 500);

            // Ensure productType doesn't exceed 10 (just in case)
            $productType = min($productType, 10);

            $productStocks[] = [
                'stock_quantity' => 1,
                'price' => $productPrices[$productType],
                'product_id' => $productId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert in chunks for better performance
        $chunks = array_chunk($productStocks, 100);
        foreach ($chunks as $chunk) {
            DB::table('product_stocks')->insert($chunk);
        }
    }
}
