<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bundles extends Model
{
    use HasFactory;

    protected $fillable = [
        'bundle_name',
        'quantity_bundles',
        'bundle_type',
    ];


    public function purchaseDetails()
    {
        return $this->hasMany(Purchase_Details::class);
    }

}