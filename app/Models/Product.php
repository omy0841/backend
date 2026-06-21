<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name','emoji','description','price','stock','unit_type','price_per_unit'];

    protected $casts = [
        'stock' => 'decimal:2',
        'price_per_unit' => 'decimal:2',
    ];
}
