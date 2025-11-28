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
            $table->string('first_name');
            $table->string('last_name');
            $table->string('gender');
            $table->string('contact_no');
            $table->string('street');
            $table->string('brgy');
            $table->string('city_province');
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
