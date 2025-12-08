<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;

class OrderService
{
    public static function recalculate($orderId)
    {
        $items = OrderItem::where('order_id', $orderId)->get();

        if ($items->count() === 0) {
            Order::where('id', $orderId)->delete();
            return;
        }

        Order::where('id', $orderId)->update([
            'total_points' => $items->sum('total_points'),
            'total_items' => $items->sum('qty'),
            'total_price' => $items->sum('total_price'),
        ]);
    }
}
