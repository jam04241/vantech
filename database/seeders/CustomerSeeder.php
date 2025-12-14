<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $Customers = [
            [
                'first_name' => 'John Doe',
                'last_name' => 'Smith',
                'gender' => 'Male', // Optional
                'contact_no' => '09171234567',
                'street' => '123 Main St',
                'brgy' => 'Brgy. 1',
                'city_province' => 'Cityville, Province',
            ],

        ];
        DB::table('customers')->insert($Customers);
    }
}
