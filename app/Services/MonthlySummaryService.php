<?php

namespace App\Services;

use App\Models\MonthlySummary;

class MonthlySummaryService
{
    /**
     * Tambah data ke summary (dipakai saat transaksi ditambahkan atau setelah update)
     */
    public function add($transaction)
    {
        $year  = $transaction->created_at->year;
        $month = $transaction->created_at->month;

        // Create jika belum ada
        $summary = MonthlySummary::firstOrCreate(
            [
                'product_id'  => $transaction->product_id,
                'customer_id' => $transaction->customer_id,
                'year'        => $year,
                'month'       => $month,
            ]
        );

        // Update summary
        $summary->increment('total_qty', $transaction->qty);
        $summary->increment('total_points', $transaction->qty * $transaction->points);
        $summary->increment('total_transactions', 1);
    }

    /**
     * Kurangi data dari summary (dipakai saat transaksi dihapus atau sebelum update)
     */
    public function subtract($transaction)
    {
        $year  = $transaction->created_at->year;
        $month = $transaction->created_at->month;

        // Ambil summary
        $summary = MonthlySummary::where([
            'product_id'  => $transaction->product_id,
            'customer_id' => $transaction->customer_id,
            'year'        => $year,
            'month'       => $month,
        ])->first();

        if (!$summary) {
            return;
        }

        // Kurangi
        $summary->decrement('total_qty', $transaction->qty);
        $summary->decrement('total_points', $transaction->qty * $transaction->points);
        $summary->decrement('total_transactions', 1);

        // Kalau summary sudah 0 semua → aman hapus untuk hemat DB
        if (
            $summary->total_qty <= 0 &&
            $summary->total_points <= 0 &&
            $summary->total_transactions <= 0
        ) {
            $summary->delete();
        }
    }
}
