<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product_Stocks;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'price',
        'brand_id',
        'category_id',
        'supplier_id',
        'warranty_period',
        'serial_number',
        'product_condition',
        // Define fillable attributes here
    ];
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Suppliers::class);
    }

    public function stock()
    {
        return $this->hasOne(Product_Stocks::class, 'product_id');
    }
}
