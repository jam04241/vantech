<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_purchase_order_id')->constrained('customer_purchase_orders')->onDelete('cascade');
            $table->string('method_name');
            $table->string('bank_name')->nullable();
            $table->string('reference_no')->nullable();
            $table->date('payment_date');
            $table->decimal('amount', 10, 2);
            $table->timestamps();

            $table->index('customer_purchase_order_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_methods');
    }
};
