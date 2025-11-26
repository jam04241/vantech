<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment_Method extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_purchase_order_id',
        'method_name',
        'payment_date',
        'amount'
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(Customer_Purchase_Order::class);
    }
}
