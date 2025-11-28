<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'sku',
        'name',
        'price',
        'points_per_unit'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
