<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customer_purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dr_receipt_id')->constrained('dr_transactions', 'id')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('customers', 'id')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products', 'id')->onDelete('cascade');
            $table->string('serial_number');
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->date('order_date');
            $table->string('status')->default('Success');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customer_purchase_orders');
    }
};
