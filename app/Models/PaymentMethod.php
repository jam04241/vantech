<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'payment_methods';
    protected $fillable = [
        'customer_purchase_order_id',
        'method_name',
        'payment_date',
        'amount'
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(CustomerPurchaseOrder::class);
    }
}
