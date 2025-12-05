<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'sku',
        'product_name',
        'qty',
        'points_per_unit',
        'total_points'
    ];

    protected $casts = [
        'qty' => 'integer',
        'points_per_unit' => 'integer',
        'total_points' => 'integer',
    ];

    /**
     * Relasi ke Order (parent)
     * Satu item dimiliki oleh satu order
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relasi ke Product
     * Satu item merujuk ke satu produk
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Calculate total points untuk item ini
     *
     * @return int
     */
    public function calculateTotalPoints(): int
    {
        return $this->qty * $this->points_per_unit;
    }

    /**
     * Boot method - Auto calculate total_points sebelum save
     */
    protected static function boot()
    {
        parent::boot();

        // Sebelum create atau update, hitung total_points otomatis
        static::saving(function ($item) {
            $item->total_points = $item->calculateTotalPoints();
        });

        // Setelah create, update totals di order parent
        static::created(function ($item) {
            $item->order->updateTotals();
        });

        // Setelah update, update totals di order parent
        static::updated(function ($item) {
            $item->order->updateTotals();
        });

        // Sebelum delete, simpan order_id untuk update nanti
        static::deleting(function ($item) {
            $item->orderToUpdate = $item->order;
        });

        // Setelah delete, update totals di order parent
        static::deleted(function ($item) {
            if (isset($item->orderToUpdate)) {
                $item->orderToUpdate->updateTotals();
            }
        });
    }

    /**
     * Accessor: Format total points untuk display
     */
    public function getFormattedTotalPointsAttribute(): string
    {
        return number_format($this->total_points, 0, ',', '.');
    }

    /**
     * Accessor: Format points per unit untuk display
     */
    public function getFormattedPointsPerUnitAttribute(): string
    {
        return number_format($this->points_per_unit, 0, ',', '.');
    }
}
