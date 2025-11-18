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
      Schema::create('purchase_details', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity_ordered');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->date('order_date');
            $table->enum('status', ['Cancelled', 'Received', 'Pending'])->default('Pending');
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('cascade');
            $table->foreignId('bundle_id')->nullable()->constrained('bundles')->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('no action');
            
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
        Schema::dropIfExists('purchase_details');
    }
};
