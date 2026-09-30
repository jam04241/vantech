<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarrantyClaim extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_purchase_order_id',
        'dr_receipt_id',
        'claim_date',
        'reason',
        'sales_amount',
        'good_cost',
        'recorded_by',
    ];

    protected $casts = [
        'claim_date' => 'date',
        'sales_amount' => 'decimal:2',
        'good_cost' => 'decimal:2',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(CustomerPurchaseOrder::class, 'customer_purchase_order_id');
    }

    public function drTransaction()
    {
        return $this->belongsTo(DRTransaction::class, 'dr_receipt_id');
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    /**
     * Claims whose sale was made in the given period.
     *
     * A claim cancels the sale it belongs to, so it counts against the period
     * of that sale (the receipt date), not the day the item came back. Every
     * period then stays consistent with its own receipts and never goes below
     * zero because of a return.
     */
    public function scopeForSalesBetween($query, CarbonInterface $start, CarbonInterface $end)
    {
        return $query->whereHas('drTransaction', function ($receipt) use ($start, $end) {
            $receipt->where('type', 'purchase')
                ->whereBetween('created_at', [$start, $end]);
        });
    }

    /**
     * Paid amounts taken out of receipt totals for sales made in the period.
     */
    public static function salesRemovedBetween(CarbonInterface $start, CarbonInterface $end): float
    {
        return round((float) static::forSalesBetween($start, $end)->sum('sales_amount'), 2);
    }

    /**
     * Good cost taken out of Total Good Cost for sales made in the period.
     */
    public static function goodCostRemovedBetween(CarbonInterface $start, CarbonInterface $end): float
    {
        return round((float) static::forSalesBetween($start, $end)->sum('good_cost'), 2);
    }

    /**
     * Amount and item count removed from each receipt, ready to join onto
     * dr_transactions by dr_receipt_id.
     */
    public static function removedPerReceipt()
    {
        return static::query()
            ->select('dr_receipt_id')
            ->selectRaw('SUM(sales_amount) as removed_amount')
            ->selectRaw('COUNT(*) as claimed_items')
            ->groupBy('dr_receipt_id');
    }
}
