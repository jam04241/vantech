<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerPurchaseOrder extends Model
{
    use HasFactory;

    protected $table = 'customer_purchase_orders';

    protected $fillable = [
        'customer_id',
        'product_id',
        'serial_number',
        'quantity',
        'unit_price',
        'total_price',
        'order_date',
        'status'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function paymentMethod()
    {
        return $this->hasOne(PaymentMethod::class);
    }
}
