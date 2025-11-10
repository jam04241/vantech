<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'brand_name',
        // Define fillable attributes here
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
    
}
