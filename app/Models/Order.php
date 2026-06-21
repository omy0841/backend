<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'contact_name',
        'hotel_name',
        'phone',
        'email',
        'delivery_location',
        'latitude',
        'longitude',
        'items',
        'special_request',
        'total_amount',
        'status',
    ];

    protected $casts = [
        'items' => 'array',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
