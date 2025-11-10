<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suppliers extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_name',
        'company_name',
        'contact_phone',
        'address',
        'status',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
