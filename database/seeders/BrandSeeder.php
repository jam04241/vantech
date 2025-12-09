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
        ['brand_name' => '1st Player'],
        ['brand_name' => 'AOC'],
        ['brand_name' => 'AMD'],
        ['brand_name' => 'ASRock'],
        ['brand_name' => 'ASUS'],
        ['brand_name' => 'BIOSTAR'],
        ['brand_name' => 'Corsair'],
        ['brand_name' => 'Cougar'],
        ['brand_name' => 'DarkFlash'],
        ['brand_name' => 'ESGAMING'],
        ['brand_name' => 'Epson'],
        ['brand_name' => 'EVGA'],
        ['brand_name' => 'Faspeed'],
        ['brand_name' => 'GALAX'],
        ['brand_name' => 'Gamdias'],
        ['brand_name' => 'Generic'],
        ['brand_name' => 'Gigabyte'],
        ['brand_name' => 'Inplay'],
        ['brand_name' => 'Intel'],
        ['brand_name' => 'Kinston'],
        ['brand_name' => 'Logitech'],
        ['brand_name' => 'MSI'],
        ['brand_name' => 'NVIDIA'],
        ['brand_name' => 'NVISION'],
        ['brand_name' => 'PNY'],
        ['brand_name' => 'Redragon'],
        ['brand_name' => 'Samsung'],
        ['brand_name' => 'Seagate'],
        ['brand_name' => 'Toshiba'],
        ['brand_name' => 'Supervision'],
        ['brand_name' => 'T-Force'],
        ['brand_name' => 'Tecware'],
        ['brand_name' => 'Thermalright'],
        ['brand_name' => 'XFX'],
        ['brand_name' => 'ZOTAC']
    ];
}
