<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Seeds the owner (admin) accounts that can sign in to the system.
 *
 * Staff accounts are NOT seeded: employees register themselves and an owner
 * activates them. These owner logins are the way in before any staff exist.
 *
 * Safe to re-run. Accounts are matched on username, so running this again
 * restores admin access (re-activating the account and resetting the password
 * to the value below) instead of failing on the unique username index.
 *
 *     php artisan db:seed --class=UserSeeder
 */
class UserSeeder extends Seeder
{
    /**
     * Owner accounts. Passwords are the development defaults - change them
     * after first login on anything real.
     */
    private const ADMINS = [
        [
            'first_name' => 'Van Bryan',
            'middle_name' => 'C.',
            'last_name' => 'Bardilas',
            'username' => 'vantech123',
            'password' => 'password123',
        ],
        [
            'first_name' => 'DEV',
            'middle_name' => '',
            'last_name' => 'BSIT',
            'username' => 'admin',
            'password' => '@Supersecret123',
        ],
    ];

    /**
     * Run the database seeds.
     * role only: staff & admin
     *
     * @return void
     */
    public function run()
    {
        // Re-seeding resets admin passwords, which would be destructive on a
        // live system. Set SEED_ADMINS_IN_PRODUCTION=true only if that is
        // genuinely what you want.
        if (app()->environment('production') && !env('SEED_ADMINS_IN_PRODUCTION', false)) {
            $this->command?->warn('UserSeeder skipped in production. Set SEED_ADMINS_IN_PRODUCTION=true to override.');

            return;
        }

        foreach (self::ADMINS as $admin) {
            $existed = DB::table('users')->where('username', $admin['username'])->exists();

            DB::table('users')->updateOrInsert(
                ['username' => $admin['username']],
                [
                    'first_name' => $admin['first_name'],
                    'middle_name' => $admin['middle_name'],
                    'last_name' => $admin['last_name'],
                    'password' => Hash::make($admin['password']),
                    'role' => 'admin',

                    // Owners bypass the approval flow. Without this they would
                    // inherit the column default of 'pending' and nobody could
                    // log in to a freshly migrated database.
                    'status' => User::STATUS_ACTIVE,
                    'approved_at' => now(),

                    'updated_at' => now(),
                ] + ($existed ? [] : ['created_at' => now()])
            );

            $this->command?->info(\sprintf(
                '  %s admin "%s" (password: %s)',
                $existed ? 'Restored' : 'Created',
                $admin['username'],
                $admin['password']
            ));
        }

        $this->command?->info('Admin accounts are active and can log in.');
    }
}
