<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase_Details extends Model
{
    use HasFactory;

    protected $table = 'purchase_details';

    protected $fillable = [
        'product_id',
        'supplier_id',
        'bundle_id',
        'quantity_ordered',
        'unit_price',
        'total_price',
        'order_date',
        'status',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'order_date' => 'date',
        'quantity_ordered' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function bundle()
    {
        return $this->belongsTo(Bundles::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Suppliers::class);
    }

    /**
     * Get the item name based on type
     */
    public function getItemDisplayNameAttribute()
    {
        if ($this->item_type === 'bundle' && $this->bundle) {
            return $this->bundle->bundle_name;
        }
        
        return $this->item_name;
    }

    /**
     * Scope for pending orders
     */
    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    /**
     * Scope for received orders
     */
    public function scopeReceived($query)
    {
        return $query->where('status', 'Received');
    }

    /**
     * Calculate total price
     */
    public function calculateTotalPrice()
    {
        return $this->quantity_ordered * $this->unit_price;
    }
}