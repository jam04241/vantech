<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Brand::insert(self::$brands);
    }

    protected static $brands   = [
        ['brand_name' => 'AMD'],
        ['brand_name' => 'Intel'],
        ['brand_name' => 'Gigabyte'],
        ['brand_name' => 'ASUS'],
        ['brand_name' => 'MSI'],
        ['brand_name' => 'NVIDIA'],
        ['brand_name' => 'EVGA'],
        ['brand_name' => 'Corsair'],
        ['brand_name' => 'Inplay'],
        ['brand_name' => 'Kinston'],
        ['brand_name' => '1st Player'],
        ['brand_name' => 'Jungle Leopard'],
        ['brand_name' => 'DarkFlash'],
    ];
}
