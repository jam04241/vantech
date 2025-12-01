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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers', 'id')->onDelete('cascade');
            $table->foreignId('service_type_id')->constrained('service_types', 'id')->onDelete('cascade');
            $table->string('type');
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->text('description');
            $table->text('action')->nullable();
            $table->string('status')->default('Pending');
            $table->decimal('total_price', 10, 2)->default(0);
            $table->date('date_in')->nullable();
            $table->date('date_out')->nullable();
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
        Schema::dropIfExists('services');
    }
};
