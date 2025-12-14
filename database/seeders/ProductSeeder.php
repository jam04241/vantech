<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productTypes = [
            [
                'product_name' => 'AMD Ryzen 7 7800X3D 8-C 16-T',
                'brand_id' => 3,
                'category_id' => 1,
                'supplier_id' => 1,
                'warranty_period' => '1 year',
                'product_condition' => 'Brand New',
            ],
            [
                'product_name' => 'ASUS Dual GeForce RTX™ 4060 OC Edition 8GB GDDR6',
                'brand_id' => 3,
                'category_id' => 3,
                'supplier_id' => 1,
                'warranty_period' => '1 year',
                'product_condition' => 'Brand New',
            ],
            [
                'product_name' => 'Samsung 990 Pro 4 TB M.2-2280 PCIe 4.0 X4 NVME SSD',
                'brand_id' => 3,
                'category_id' => 6,
                'supplier_id' => 1,
                'warranty_period' => '1 year',
                'product_condition' => 'Brand New',
            ],
            [
                'product_name' => 'Asus ROG LOKI 1200W 80+ Titanium Fully Modular SFX Power Supply',
                'brand_id' => 5,
                'category_id' => 7,
                'supplier_id' => 1,
                'warranty_period' => '1 year',
                'product_condition' => 'Brand New',
            ],
            [
                'product_name' => 'Dark Flash WD200 M-ATX PC Case',
                'brand_id' => 9,
                'category_id' => 8,
                'supplier_id' => 1,
                'warranty_period' => '1 year',
                'product_condition' => 'Brand New',
            ],
            [
                'product_name' => 'MSI G244F 23.8" 1920 x 1080 170 Hz Monitor',
                'brand_id' => 22,
                'category_id' => 9,
                'supplier_id' => 1,
                'warranty_period' => '1 year',
                'product_condition' => 'Brand New',
            ],
            [
                'product_name' => 'Thermalright Frozen Warframe 360 WHITE ARGB',
                'brand_id' => 33,
                'category_id' => 4,
                'supplier_id' => 1,
                'warranty_period' => '1 year',
                'product_condition' => 'Brand New',
            ],
            [
                'product_name' => 'MSI B650M GAMING PLUS WIFI mATX',
                'brand_id' => 22,
                'category_id' => 2,
                'supplier_id' => 1,
                'warranty_period' => '1 year',
                'product_condition' => 'Brand New',
            ],
            [
                'product_name' => 'Corsair Vengeance RGB DDR5 16GB 6000MHz',
                'brand_id' => 7,
                'category_id' => 5,
                'supplier_id' => 1,
                'warranty_period' => '1 year',
                'product_condition' => 'Brand New',
            ],
            [
                'product_name' => 'Epson L3150 EcoTank All-in-One Ink Tank Printer',
                'brand_id' => 11,
                'category_id' => 10,
                'supplier_id' => 1,
                'warranty_period' => '1 year',
                'product_condition' => 'Brand New',
            ],
        ];

        $allProducts = [];
        $productCounter = 1;

        foreach ($productTypes as $productType) {
            for ($i = 1; $i <= 500; $i++) {
                $product = $productType;
                $product['serial_number'] = 'SN' . str_pad($productCounter, 7, '0', STR_PAD_LEFT);
                $product['created_at'] = now();
                $product['updated_at'] = now();

                $allProducts[] = $product;
                $productCounter++;
            }
        }

        // Insert in chunks to avoid memory issues
        $chunks = array_chunk($allProducts, 100);
        foreach ($chunks as $chunk) {
            DB::table('products')->insert($chunk);
        }
    }
}
