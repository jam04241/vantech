<?php

namespace App\Services;

use App\Models\DRTransaction;
use Illuminate\Support\Facades\DB;

class DRTransactionService
{
    /**
     * Generate unique DR receipt number
     * Format: YYYY-XXXXX (e.g., 2025-00001, grows dynamically: 2025-99999 -> 2025-100000)
     */
    public function generateDRNumber(): string
    {
        $year = date('Y');

        // Get the last DR number for current year
        $lastDR = DRTransaction::where('receipt_no', 'LIKE', "$year-%")
            ->orderBy('receipt_no', 'desc')
            ->first();

        if ($lastDR) {
            // Extract the increment part (everything after the dash)
            $parts = explode('-', $lastDR->receipt_no);
            $lastNumber = (int) $parts[1];
            $newNumber = $lastNumber + 1;
        } else {
            // First DR for this year
            $newNumber = 1;
        }

        // Format with minimum 5 digits, but grows dynamically beyond 99999
        // 1-99999: zero-padded to 5 digits (00001-99999)
        // 100000+: natural length (100000, 100001, etc.)
        $paddedNumber = str_pad($newNumber, 5, '0', STR_PAD_LEFT);

        return sprintf('%s-%s', $year, $paddedNumber);
    }

    /**
     * Create DR transaction
     */
    public function createDRTransaction(string $type, float $totalSum): DRTransaction
    {
        $receiptNo = $this->generateDRNumber();

        return DRTransaction::create([
            'receipt_no' => $receiptNo,
            'type' => $type,
            'total_sum' => $totalSum
        ]);
    }
}
