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
        Schema::create('customers', function (Blueprint $table) {
            $table->id(); // BIGINT unsigned, auto-increment
            $table->string('first_name'); // Required
            $table->string('last_name')->nullable(); // Required
            $table->string('gender')->nullable(); // Required
            $table->string('contact_no')->nullable(); // Required
            $table->string('street')->nullable(); // Optional
            $table->string('brgy')->nullable(); // Optional
            $table->string('city_province')->nullable(); // Optional
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
        Schema::dropIfExists('customers');
    }
};
