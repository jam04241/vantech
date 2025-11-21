<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Suppliers;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Suppliers::insert(self::$Suppliers);
    }

    protected static $Suppliers = [
        [
            'supplier_name' => 'Xi Jing Ping',
            'company_name'  => 'Hcplay Computer Davao 的小组',
            'contact_phone' => '0822939460',
            'address'       => '7 Nicasio Torres St, Davao City, 8000 Davao del Sur',
            'status'        => 'active',
        ],
        [
            'supplier_name' => 'Chow Chow',
            'company_name'  => 'PC Chao',
            'contact_phone' => '09987654321',
            'address'       => 'Quezon City, Philippines',
            'status'        => 'active',
        ]
    ];
}
