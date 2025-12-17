<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'customer_id',
        'total_points',
        'total_items',
        'price', 
        'notes',
    ];

    protected $casts = [
        'total_points' => 'integer',
        'total_items' => 'integer',
        'price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Accessors
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d M Y, H:i');
    }

    public function getDateAttribute()
    {
        return $this->created_at->format('d M Y');
    }

    // Update totals dari items
    // public function updateTotals()
    // {
    //     $this->total_items = $this->items->sum('qty');
    //     $this->total_points = $this->items->sum('total_points');
    //     $this->total_price = $this->items->sum('total_price');

    //     $this->save();
    // }
}
