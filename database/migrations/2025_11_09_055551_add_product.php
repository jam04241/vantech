<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->foreignId('brand_id')->constrained('brands', 'id')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories', 'id')->onDelete('cascade');
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers', 'id')->onDelete('cascade'); // Make nullable
            $table->string('warranty_period');
            $table->enum('product_condition', ['Brand New', 'Second Hand'])->default('Brand New');
            $table->string('serial_number');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};