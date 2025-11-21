<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Product_Stocks extends Model
{
    use HasFactory;

    protected $table = 'product_stocks';

    protected $fillable = [
        'stock_quantity',
        'price',
        'product_id',
    ];

    protected $casts = [
        'stock_quantity' => 'integer',
        'price' => 'float',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Support legacy table name while transition is in progress.
     *
     * @return string
     */
    public function getTable()
    {
        $table = parent::getTable();

        if (Schema::hasTable($table)) {
            return $table;
        }

        $legacy = 'product__stocks';
        if ($table !== $legacy && Schema::hasTable($legacy)) {
            return $legacy;
        }

        return $table;
    }
}
