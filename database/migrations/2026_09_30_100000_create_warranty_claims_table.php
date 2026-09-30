<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * A warranty claim records a sold item that came back broken while it was
     * still under warranty. It cancels that one sale line: the line's status
     * becomes "Warranty Claim" and the amounts below come out of the reports.
     *
     * The receipt (dr_transactions.total_sum) and the sale line itself are left
     * as billed, so reprints and customer history still show the original sale.
     */
    public function up(): void
    {
        Schema::create('warranty_claims', function (Blueprint $table) {
            $table->id();
            // One claim per sold item.
            $table->foreignId('customer_purchase_order_id')->unique()
                ->constrained('customer_purchase_orders')->onDelete('cascade');
            $table->foreignId('dr_receipt_id')->constrained('dr_transactions')->onDelete('cascade');
            $table->date('claim_date');
            $table->text('reason');
            // The item's share of what the customer paid, after the receipt discount.
            $table->decimal('sales_amount', 10, 2);
            // What the item cost the shop; removed from Total Good Cost.
            $table->decimal('good_cost', 10, 2);
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Without the claims the lines would stay hidden from sales while their
        // receipts still counted in full, so hand them back to normal sales.
        DB::table('customer_purchase_orders')
            ->where('status', 'Warranty Claim')
            ->update(['status' => 'Success']);

        Schema::dropIfExists('warranty_claims');
    }
};
