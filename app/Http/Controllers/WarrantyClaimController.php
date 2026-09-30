<?php

namespace App\Http\Controllers;

use App\Models\CustomerPurchaseOrder;
use App\Models\Product;
use App\Models\Product_Stocks;
use App\Models\WarrantyClaim;
use App\Services\WarrantyClaimService;
use App\Traits\LogsAuditTrail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WarrantyClaimController extends Controller
{
    use LogsAuditTrail;

    public function __construct(private WarrantyClaimService $claims)
    {
    }

    /**
     * Mark a sold item as returned broken under warranty.
     */
    public function store(Request $request, CustomerPurchaseOrder $purchaseOrder)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:1000',
            'good_cost' => 'required|numeric|min:0|max:99999999.99',
        ]);

        $claim = $this->claims->record(
            $purchaseOrder,
            trim($validated['reason']),
            (float) $validated['good_cost'],
            Auth::id()
        );

        $claim->load('purchaseOrder.product', 'drTransaction');
        $line = $claim->purchaseOrder;
        $salesAmount = number_format((float) $claim->sales_amount, 2);
        $goodCost = number_format((float) $claim->good_cost, 2);

        $this->logAudit(
            'UPDATE',
            'Inventory',
            "Warranty claim: {$line->product->product_name} (SN: {$line->serial_number}) on receipt "
                . "{$claim->drTransaction->receipt_no} - removed ₱{$salesAmount} sales and ₱{$goodCost} good cost",
            [
                'warranty_claim_id' => $claim->id,
                'reason' => $claim->reason,
                'sales_amount' => $claim->sales_amount,
                'good_cost' => $claim->good_cost,
            ],
            $request
        );

        return response()->json([
            'success' => true,
            'message' => "Removed ₱{$salesAmount} from sales and ₱{$goodCost} from good cost.",
            'stats' => $this->stockOutStats(),
        ]);
    }

    /**
     * Undo a claim entered by mistake. Owner only: it puts the sale back.
     */
    public function destroy(Request $request, WarrantyClaim $warrantyClaim)
    {
        if (Auth::user()?->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Only the owner can undo a warranty claim.',
            ], 403);
        }

        $warrantyClaim->load('purchaseOrder.product', 'drTransaction');
        $line = $warrantyClaim->purchaseOrder;

        $this->claims->undo($warrantyClaim);

        $this->logAudit(
            'UPDATE',
            'Inventory',
            "Undid warranty claim: {$line->product->product_name} (SN: {$line->serial_number}) on receipt "
                . "{$warrantyClaim->drTransaction->receipt_no} - sale counts again",
            [
                'warranty_claim_id' => $warrantyClaim->id,
                'sales_amount' => $warrantyClaim->sales_amount,
                'good_cost' => $warrantyClaim->good_cost,
            ],
            $request
        );

        return response()->json([
            'success' => true,
            'message' => 'Warranty claim removed. The sale counts again.',
            'stats' => $this->stockOutStats(),
        ]);
    }

    /**
     * Figures for the Stock-Out cards, so the page can refresh them in place.
     */
    public static function stockOutStats(): array
    {
        $claimed = fn ($lines) => $lines->where('status', CustomerPurchaseOrder::STATUS_WARRANTY_CLAIM);

        return [
            'totalStockOuts' => Product_Stocks::where('stock_quantity', 0)->count(),
            'totalSoldProducts' => Product::whereHas('stock', fn ($stock) => $stock->where('stock_quantity', 0))
                ->whereDoesntHave('customerPurchaseOrders', $claimed)
                ->count(),
            'totalWarrantyClaims' => WarrantyClaim::count(),
        ];
    }
}
