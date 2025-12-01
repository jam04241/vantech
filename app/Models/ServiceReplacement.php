<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceReplacement extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'item_name',
        'old_item_condition',
        'new_item',
        'new_item_price',
        'new_item_warranty',
        'is_disabled',
    ];

    protected $casts = [
        'new_item_price' => 'decimal:2',
    ];

    // Relationships
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
