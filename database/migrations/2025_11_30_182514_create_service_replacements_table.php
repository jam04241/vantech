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
        Schema::create('service_replacements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services', 'id')->onDelete('cascade');
            $table->string('item_name');
            $table->text('old_item_condition');
            $table->text('new_item');
            $table->decimal('new_item_price', 10, 2);
            $table->string('new_item_warranty')->nullable();
            $table->boolean('is_disabled')->default(1);
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
        Schema::dropIfExists('service_replacements');
    }
};
