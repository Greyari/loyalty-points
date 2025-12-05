<?php

namespace App\Services;

use App\Models\MonthlySummary;
use App\Models\OrderItem;
use Carbon\Carbon;

class MonthlySummaryService
{
    /**
     * Tambah summary dari order item
     */
    public function add(OrderItem $item)
    {
        $date = Carbon::parse($item->created_at);

        $summary = MonthlySummary::firstOrCreate([
            'product_id'  => $item->product_id,
            'customer_id' => $item->order->customer_id,
            'year'        => $date->year,
            'month'       => $date->month,
        ]);

        $summary->increment('total_qty', $item->qty);
        $summary->increment('total_points', $item->total_points);
        $summary->increment('total_transactions');
    }

    /**
     * Kurangi summary dari order item (misal update/hapus)
     */
    public function subtract(OrderItem $item)
    {
        $date = Carbon::parse($item->created_at);

        $summary = MonthlySummary::where([
            'product_id'  => $item->product_id,
            'customer_id' => $item->order->customer_id,
            'year'        => $date->year,
            'month'       => $date->month,
        ])->first();

        if (!$summary) return;

        $summary->decrement('total_qty', $item->qty);
        $summary->decrement('total_points', $item->total_points);
        $summary->decrement('total_transactions');
    }
}
