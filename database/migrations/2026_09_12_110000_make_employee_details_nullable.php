<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Applicants no longer supply their address or position when registering -
     * the owner fills those in from the staff record once they review the
     * application. Those columns therefore have to allow "not set yet".
     *
     * Relaxing NOT NULL keeps every existing row exactly as it is.
     */
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('street')->nullable()->change();
            $table->string('barangay')->nullable()->change();
            $table->string('city')->nullable()->change();
            $table->string('role')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Backfill before restoring NOT NULL, otherwise rows the owner has not
        // completed yet would block the rollback.
        foreach (['street', 'barangay', 'city', 'role'] as $column) {
            \Illuminate\Support\Facades\DB::table('employees')
                ->whereNull($column)
                ->update([$column => '']);
        }

        Schema::table('employees', function (Blueprint $table) {
            $table->string('street')->nullable(false)->change();
            $table->string('barangay')->nullable(false)->change();
            $table->string('city')->nullable(false)->change();
            $table->string('role')->nullable(false)->change();
        });
    }
};
