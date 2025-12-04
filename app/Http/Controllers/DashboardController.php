<?php

namespace App\Http\Controllers;

use App\Models\MonthlySummary;

class DashboardController extends Controller
{
    public function index()
    {
        $currentYear  = now()->year;
        $currentMonth = now()->month;

        // Top customer bulan ini
        $topCustomers = MonthlySummary::where('year', $currentYear)
            ->where('month', $currentMonth)
            ->with('customer')
            ->selectRaw('customer_id, SUM(total_points) as points')
            ->groupBy('customer_id')
            ->orderByDesc('points')
            ->take(5)
            ->get();

        // Produk terlaris bulan ini
        $topProducts = MonthlySummary::where('year', $currentYear)
            ->where('month', $currentMonth)
            ->with('product')
            ->selectRaw('product_id, SUM(total_qty) as qty')
            ->groupBy('product_id')
            ->orderByDesc('qty')
            ->take(5)
            ->get();

        return view('home.dashboard', compact('topCustomers', 'topProducts'));
    }

    public function data()
    {
        $currentYear  = now()->year;
        $currentMonth = now()->month;

        // Top customer
        $topCustomers = MonthlySummary::where('year', $currentYear)
            ->where('month', $currentMonth)
            ->with('customer:id,name')
            ->selectRaw('customer_id, SUM(total_points) as points')
            ->groupBy('customer_id')
            ->orderByDesc('points')
            ->take(5)
            ->get();

        // Top product
        $topProducts = MonthlySummary::where('year', $currentYear)
            ->where('month', $currentMonth)
            ->with('product:id,name,sku')
            ->selectRaw('product_id, SUM(total_qty) as qty')
            ->groupBy('product_id')
            ->orderByDesc('qty')
            ->take(5)
            ->get();

        return response()->json([
            'success' => true,
            'topCustomers' => $topCustomers,
            'topProducts'  => $topProducts,
        ]);
    }

}
