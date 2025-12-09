<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dr_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_no')->unique(); // Format: YYYY-XXXXX (e.g., 2025-00001)
            $table->string('type'); // values : 'purchase', 'acknowledgement', 'service_completed'
            $table->decimal('total_sum', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dr_transactions');
    }
};
