<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'contact_no',
        'street',
        'brgy',
        'city_province',
        // Define fillable attributes here
    ];
}
