<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Employees now register themselves, so a login account exists before the
     * owner has approved it. This adds the approval state, the link to the
     * employee's staff record, and the unique username that self-registration
     * depends on.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('role');
            $table->foreignId('employee_id')->nullable()->after('status')
                ->constrained('employees')->nullOnDelete();
            $table->timestamp('approved_at')->nullable()->after('employee_id');
            $table->foreignId('approved_by')->nullable()->after('approved_at')
                ->constrained('users')->nullOnDelete();
        });

        // Existing accounts predate approval and must keep working.
        DB::table('users')->update([
            'status' => 'active',
            'approved_at' => now(),
        ]);

        // Self-registration picks the username, so it has to be unique. Without
        // this two applicants could claim the same login.
        Schema::table('users', function (Blueprint $table) {
            $table->unique('username');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropConstrainedForeignId('approved_by');
            $table->dropColumn('approved_at');
            $table->dropConstrainedForeignId('employee_id');
            $table->dropColumn('status');
        });
    }
};
