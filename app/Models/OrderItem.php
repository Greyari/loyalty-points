<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'sku',
        'product_name',
        'qty',
        'points_per_unit',
        'total_points',
        'price_per_unit',
        'total_price',
    ];

    protected $casts = [
        'qty' => 'integer',
        'points_per_unit' => 'integer',
        'total_points' => 'integer',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
