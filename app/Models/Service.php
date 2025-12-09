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

    /**
     * Get only active (non-disabled) replacements for this service
     * This is the default relationship used when fetching service details
     * is_disabled = 0 means active/visible
     * is_disabled = 1 means soft-deleted/hidden
     */
    public function replacements()
    {
        return $this->hasMany(ServiceReplacement::class)->where('is_disabled', 0);
    }

    /**
     * Get all replacements including disabled ones (for admin/management purposes)
     */
    public function allReplacements()
    {
        return $this->hasMany(ServiceReplacement::class);
    }
}
