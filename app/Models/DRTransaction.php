<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DRTransaction extends Model
{
    use HasFactory;

    protected $table = 'dr_transactions';

    protected $fillable = [
        'receipt_no',
        'type',
        'total_sum'
    ];

    protected $casts = [
        'total_sum' => 'decimal:2'
    ];

    /**
     * Get customer purchase orders for this DR
     */
    public function customerPurchaseOrders()
    {
        return $this->hasMany(CustomerPurchaseOrder::class, 'dr_receipt_id');
    }

    /**
     * Get services for this DR
     */
    public function services()
    {
        return $this->hasMany(Service::class, 'dr_receipt_id');
    }
}
