<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceReplacementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // This seeder is for service replacements associated with services
        // Service replacements are created when a service is created with replacement items
        // No initial seeding needed for service_replacements table as it's populated dynamically

        // If you want to seed service types instead, they should go in service_types table
        // This is left empty as replacements are created through the application
    }
}
