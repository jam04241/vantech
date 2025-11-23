<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer_Purchase_Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'customer_id',
        'quantity',
        'unit_price',
        'total_price',
        'order_date',
        'status',
    ];
}
