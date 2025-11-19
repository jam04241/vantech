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
        if (Schema::hasTable('product__stocks') && !Schema::hasTable('product_stocks')) {
            Schema::rename('product__stocks', 'product_stocks');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('product_stocks') && !Schema::hasTable('product__stocks')) {
            Schema::rename('product_stocks', 'product__stocks');
        }
    }
};

