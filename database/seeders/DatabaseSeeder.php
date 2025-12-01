<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
            SupplierSeeder::class,
            BrandSeeder::class,
            ProductSeeder::class,
            UserSeeder::class,
            ServiceTypeSeeder::class,
            ServiceReplacementSeeder::class,
        ]);
    }
}
