<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Walk-in buyers often do not give a name, so a POS sale must be able to
     * record no customer at all.
     *
     * Storing NULL is preferred over creating a placeholder "Walk-in" customer
     * row: a placeholder would show up in the customer records list and in the
     * top-customers report as though it were a real person.
     *
     * Relaxing NOT NULL leaves every existing order untouched.
     */
    public function up(): void
    {
        Schema::table('customer_purchase_orders', function (Blueprint $table) {
            // Drop and re-add the foreign key so it can be re-pointed at a
            // nullable column with the right delete behaviour.
            $table->dropForeign(['customer_id']);
        });

        Schema::table('customer_purchase_orders', function (Blueprint $table) {
            $table->foreignId('customer_id')->nullable()->change();
        });

        Schema::table('customer_purchase_orders', function (Blueprint $table) {
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        // Anonymous sales cannot be expressed once the column is NOT NULL
        // again, so they are removed rather than silently reassigned to
        // somebody else's account.
        \Illuminate\Support\Facades\DB::table('customer_purchase_orders')
            ->whereNull('customer_id')
            ->delete();

        Schema::table('customer_purchase_orders', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
        });

        Schema::table('customer_purchase_orders', function (Blueprint $table) {
            $table->foreignId('customer_id')->nullable(false)->change();
        });

        Schema::table('customer_purchase_orders', function (Blueprint $table) {
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }
};
