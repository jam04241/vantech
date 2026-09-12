<?php

namespace App\Services;

use App\Models\DRTransaction;
use Illuminate\Database\QueryException;
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

        return $this->formatDRNumber($year, $this->nextDRNumber($year));
    }

    /**
     * Next increment for the given year.
     *
     * Takes the highest increment numerically rather than by string order. Sorting
     * receipt_no as text ranked "2025-99999" above "2025-100000", so the counter
     * would restart at 100000 and collide with the unique index once the shop
     * passed its 99,999th receipt in a year.
     */
    private function nextDRNumber(string $year): int
    {
        $lastNumber = (int) DRTransaction::where('receipt_no', 'LIKE', "$year-%")
            ->max(DB::raw('CAST(SUBSTRING(receipt_no, ' . (strlen($year) + 2) . ') AS UNSIGNED)'));

        return $lastNumber + 1;
    }

    /**
     * Format with minimum 5 digits, but grows dynamically beyond 99999.
     * 1-99999: zero-padded to 5 digits (00001-99999)
     * 100000+: natural length (100000, 100001, etc.)
     */
    private function formatDRNumber(string $year, int $number): string
    {
        return sprintf('%s-%s', $year, str_pad((string) $number, 5, '0', STR_PAD_LEFT));
    }

    /**
     * Create DR transaction
     */
    public function createDRTransaction(string $type, float $totalSum): DRTransaction
    {
        // receipt_no is uniquely indexed, so two terminals checking out at the same
        // instant can generate the same number and one insert will fail. Step the
        // counter forward and retry instead of failing the sale.
        //
        // The counter is advanced locally rather than re-read: this runs inside the
        // caller's open transaction, whose snapshot will not show the competing row
        // that was just committed, so re-reading would return the same number again.
        $year = date('Y');
        $number = $this->nextDRNumber($year);

        for ($attempt = 1; ; $attempt++, $number++) {
            try {
                return DRTransaction::create([
                    'receipt_no' => $this->formatDRNumber($year, $number),
                    'type' => $type,
                    'total_sum' => $totalSum
                ]);
            } catch (QueryException $e) {
                if ($attempt >= 10 || !$this->isDuplicateReceiptNo($e)) {
                    throw $e;
                }
            }
        }
    }

    /**
     * Whether a query failure was caused by the receipt_no unique index.
     */
    private function isDuplicateReceiptNo(QueryException $e): bool
    {
        return $e->getCode() === '23000' && str_contains($e->getMessage(), 'receipt_no');
    }
}
