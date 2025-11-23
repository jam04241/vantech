<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products', 'id')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('customers', 'id')->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->date('order_date');
            $table->string('status')->default('Success');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_purchase_orders');
    }
};
