<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    // Field yang bisa diisi mass-assignment
    protected $fillable = [
        'order_id',
        'customer_id',
        'total_points',
        'total_items',
        'notes',
    ];

    /**
     * Relasi ke customer
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Relasi ke order items
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Hitung dan update total items dan total points
     */
    public function updateTotals()
    {
        $this->total_items = $this->items()->sum('qty'); // total jumlah produk
        $this->total_points = $this->items()->sum('total_points'); // total points
        $this->save();
    }
}
