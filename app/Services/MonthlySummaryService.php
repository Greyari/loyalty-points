<?php

namespace App\Services;

use App\Models\MonthlySummary;
use App\Models\PointTransaction;
use Carbon\Carbon;

class MonthlySummaryService
{
    public function add(PointTransaction $trx)
    {
        $date = Carbon::parse($trx->created_at);

        $summary = MonthlySummary::firstOrCreate([
            'product_id'  => $trx->product_id,
            'customer_id' => $trx->customer_id,
            'year'        => $date->year,
            'month'       => $date->month,
        ]);

        $summary->increment('total_qty', $trx->qty);
        $summary->increment('total_points', $trx->points);
        $summary->increment('total_transactions');
    }

    public function subtract(PointTransaction $trx)
    {
        $date = Carbon::parse($trx->created_at);

        $summary = MonthlySummary::where([
            'product_id'  => $trx->product_id,
            'customer_id' => $trx->customer_id,
            'year'        => $date->year,
            'month'       => $date->month,
        ])->first();

        if (!$summary) return;

        $summary->decrement('total_qty', $trx->qty);
        $summary->decrement('total_points', $trx->points);
        $summary->decrement('total_transactions');
    }
}
