<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'first_name' => 'Van Bryan',
                'middle_name' => 'C.',
                'last_name' => 'Bardillas',
                'username' => 'vantech123',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ],
            [
                'first_name' => 'Andrew',
                'middle_name' => '',
                'last_name' => 'Suico',
                'username' => 'doydoy123',
                'password' => Hash::make('doydoy123'),
                'role' => 'staff',
            ],
            [
                'first_name' => 'Wala Pako',
                'middle_name' => '',
                'last_name' => 'Kabalo',
                'username' => 'asawanikuya',
                'password' => Hash::make('asawanikuya'),
                'role' => 'admin',
            ],
        ];

        foreach ($users as &$emp) {
            $emp['created_at'] = now();
            $emp['updated_at'] = now();
        }

        DB::table('users')->insert($users);
    }
}
