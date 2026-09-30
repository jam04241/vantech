<?php

namespace App\Services;

use App\Models\CustomerPurchaseOrder;
use App\Models\DRTransaction;
use App\Models\WarrantyClaim;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Warranty claims: a sold item that came back broken while still covered.
 *
 * Recording a claim cancels that one sale line. Sales figures that add up
 * sale lines skip it because its status is no longer "Success"; figures built
 * from receipt totals subtract the claim's sales_amount; Total Good Cost
 * subtracts the claim's good_cost.
 */
class WarrantyClaimService
{
    /**
     * Why this sale line can't be claimed today, or null when it can.
     */
    public function ineligibilityReason(CustomerPurchaseOrder $line, ?Carbon $on = null): ?string
    {
        $on = ($on ?? Carbon::today())->copy()->startOfDay();

        if ($line->status === CustomerPurchaseOrder::STATUS_WARRANTY_CLAIM || $line->warrantyClaim) {
            return 'This item already has a warranty claim.';
        }

        if ($line->status !== CustomerPurchaseOrder::STATUS_SUCCESS) {
            return 'Only completed sales can be claimed.';
        }

        $product = $line->product;

        if (!$product || !$product->hasWarranty()) {
            return 'This item was sold without a warranty.';
        }

        $expiresOn = $product->warrantyExpiresOn($line->order_date);

        if (!$expiresOn) {
            return "The warranty period \"{$product->warranty_label}\" can't be read.";
        }

        if ($on->gt($expiresOn)) {
            return 'The warranty expired on ' . $expiresOn->format('M d, Y') . '.';
        }

        return null;
    }

    /**
     * The part of the receipt total that belongs to this line.
     *
     * A receipt discount is spread over its lines by price, so cancelling one
     * line removes what the customer actually paid for it. The last line still
     * sold on a receipt takes whatever is left, so rounding never strands a
     * centavo and a receipt can never go below zero.
     */
    public function salesAmountFor(CustomerPurchaseOrder $line): float
    {
        $receipt = $line->drTransaction;
        $lines = CustomerPurchaseOrder::where('dr_receipt_id', $receipt->id)
            ->get(['id', 'total_price', 'status']);

        $paid = (float) $receipt->total_sum;
        $subtotal = (float) $lines->sum('total_price');
        $remaining = round(
            $paid - (float) WarrantyClaim::where('dr_receipt_id', $receipt->id)->sum('sales_amount'),
            2
        );

        $othersStillSold = $lines
            ->where('id', '!=', $line->id)
            ->where('status', CustomerPurchaseOrder::STATUS_SUCCESS)
            ->count();

        if ($othersStillSold === 0) {
            return max(0.0, $remaining);
        }

        if ($subtotal <= 0) {
            return 0.0;
        }

        $share = round((float) $line->total_price * $paid / $subtotal, 2);

        return max(0.0, min($share, $remaining));
    }

    /**
     * Record the claim and take the line out of sales, all or nothing.
     *
     * @throws ValidationException when the line can't be claimed.
     */
    public function record(CustomerPurchaseOrder $line, string $reason, float $goodCost, ?int $userId): WarrantyClaim
    {
        return DB::transaction(function () use ($line, $reason, $goodCost, $userId) {
            // Lock the receipt so two claims on the same receipt are worked out
            // one after the other; the share calculation reads its other lines.
            DRTransaction::whereKey($line->dr_receipt_id)->lockForUpdate()->first();

            $line = CustomerPurchaseOrder::with(['product', 'drTransaction', 'warrantyClaim'])
                ->lockForUpdate()
                ->findOrFail($line->id);

            if ($reasonBlocked = $this->ineligibilityReason($line)) {
                throw ValidationException::withMessages(['claim' => $reasonBlocked]);
            }

            $claim = WarrantyClaim::create([
                'customer_purchase_order_id' => $line->id,
                'dr_receipt_id' => $line->dr_receipt_id,
                'claim_date' => Carbon::today(),
                'reason' => $reason,
                'sales_amount' => $this->salesAmountFor($line),
                'good_cost' => round($goodCost, 2),
                'recorded_by' => $userId,
            ]);

            $line->update(['status' => CustomerPurchaseOrder::STATUS_WARRANTY_CLAIM]);

            return $claim;
        });
    }

    /**
     * Put the line back into sales, e.g. after a claim entered by mistake.
     */
    public function undo(WarrantyClaim $claim): void
    {
        DB::transaction(function () use ($claim) {
            DRTransaction::whereKey($claim->dr_receipt_id)->lockForUpdate()->first();

            CustomerPurchaseOrder::whereKey($claim->customer_purchase_order_id)
                ->update(['status' => CustomerPurchaseOrder::STATUS_SUCCESS]);

            $claim->delete();
        });
    }
}
