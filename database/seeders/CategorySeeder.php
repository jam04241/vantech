<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::insert(self::$categories);
    }
    /**
     * The car model data.
     *
     * @var array
     */
    protected static $categories   = [
        ['category_name' => 'CPU'],
        ['category_name' => 'MOBO'],
        ['category_name' => 'GPU'],
        ['category_name' => 'CPU COOLER'],
        ['category_name' => 'RAM'],
        ['category_name' => 'STORAGE'],
        ['category_name' => 'PSU'],
        ['category_name' => 'PC CASE'],
        ['category_name' => 'PC BUILD'],
        ['category_name' => 'PERIPHERALS'],
    ];
}
