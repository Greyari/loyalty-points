<?php

namespace App\Http\Controllers;

use App\Models\MonthlySummary;
use App\Models\PointTransaction;
use Illuminate\Http\Request;

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

        // Last 5 transactions
        $recentTransactions = PointTransaction::with('customer')
            ->latest()
            ->take(5)
            ->get();

        $totalSales = PointTransaction::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->selectRaw('SUM(qty * points) as total')
            ->value('total');

        return view('home.dashboard', compact('topCustomers', 'topProducts', 'recentTransactions', 'totalSales'));
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

    public function chartData(Request $request)
    {
        $query = MonthlySummary::selectRaw('year, month, SUM(total_qty) as qty_sum')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month');

        // Filter by year if provided
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        // Filter by month if provided
        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }

        $data = $query->get();

        return response()->json([
            'categories' => $data->map(function($x) {
                return date('M Y', strtotime("{$x->year}-{$x->month}-01"));
            })->toArray(),
            'qty' => $data->pluck('qty_sum')->toArray(),
        ]);
    }

    public function getAvailableMonths(Request $request)
    {
        $query = MonthlySummary::select('month')
            ->distinct()
            ->orderBy('month');

        // Filter by year if provided
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        $months = $query->pluck('month')->toArray();

        return response()->json([
            'months' => $months
        ]);
    }
}
