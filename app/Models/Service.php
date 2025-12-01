<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'service_type_id',
        'type',
        'brand',
        'model',
        'date_in',
        'date_out',
        'description',
        'action',
        'status',
        'total_price',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'date_in' => 'date',
        'date_out' => 'date',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function replacements()
    {
        return $this->hasMany(ServiceReplacement::class);
    }
}
