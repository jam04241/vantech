<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $serviceTypes = [
            [
                'name' => 'Troubleshooting',
                'price' => 500,
            ],
            [
                'name' => 'PC Deep Cleaning',
                'price' => 1000,
            ],
            [
                'name' => 'OS/Software Installation',
                'price' => 500,
            ],
            [
                'name' => 'Repair',
                'price' => 500,
            ],
        ];

        foreach ($serviceTypes as &$type) {
            $type['created_at'] = now();
            $type['updated_at'] = now();
        }

        DB::table('service_types')->insert($serviceTypes);
    }
}
