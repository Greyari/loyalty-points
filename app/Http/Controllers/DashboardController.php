<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard dengan statistik
     */
    public function index()
    {
        $currentYear = now()->year;
        $currentMonth = now()->month;

        // Top customer bulan ini (berdasarkan total points dari orders)
        $topCustomers = Order::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->with('customer:id,name')
            ->selectRaw('customer_id, SUM(total_points) as points')
            ->groupBy('customer_id')
            ->orderByDesc('points')
            ->take(5)
            ->get();

        // Produk terlaris bulan ini (berdasarkan qty dari order_items)
        $topProducts = OrderItem::whereHas('order', function($q) use ($currentYear, $currentMonth) {
                $q->whereYear('created_at', $currentYear)
                  ->whereMonth('created_at', $currentMonth);
            })
            ->with('product:id,name,sku,points_per_unit')
            ->selectRaw('product_id, SUM(qty) as qty, SUM(total_points) as total_points')
            ->groupBy('product_id')
            ->orderByDesc('qty')
            ->take(5)
            ->get();

        // Last 5 transactions (recent orders)
        $recentTransactions = Order::with(['customer:id,name', 'items:id,order_id,qty,total_points'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function($order) {
                return [
                    'id' => $order->id,
                    'order_id' => $order->order_id,
                    'customer' => $order->customer,
                    'total_points' => $order->total_points,
                    'qty' => $order->total_items,
                    'created_at' => $order->created_at,
                ];
            });

        // Total sales bulan ini (sum of points)
        $totalSales = Order::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('total_price');

        return view('home.dashboard', compact(
            'topCustomers',
            'topProducts',
            'recentTransactions',
            'totalSales'
        ));
    }

    /**
     * API endpoint untuk chart data (opsional - jika ada chart)
     */
    public function chartData(Request $request)
    {
        // Query orders berdasarkan bulan
        $query = Order::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(total_items) as qty_sum, SUM(total_points) as points_sum')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month');

        // Filter by year if provided
        if ($request->filled('year')) {
            $query->whereYear('created_at', $request->year);
        }

        // Filter by month if provided
        if ($request->filled('month')) {
            $query->whereMonth('created_at', $request->month);
        }

        $data = $query->get();

        return response()->json([
            'categories' => $data->map(function($x) {
                return date('M Y', strtotime("{$x->year}-{$x->month}-01"));
            })->toArray(),
            'qty' => $data->pluck('qty_sum')->toArray(),
            'points' => $data->pluck('points_sum')->toArray(),
        ]);
    }

    /**
     * API endpoint untuk mendapatkan bulan yang tersedia
     */
    public function getAvailableMonths(Request $request)
    {
        $query = Order::selectRaw('DISTINCT MONTH(created_at) as month')
            ->orderBy('month');

        // Filter by year if provided
        if ($request->filled('year')) {
            $query->whereYear('created_at', $request->year);
        }

        $months = $query->pluck('month')->toArray();

        return response()->json([
            'months' => $months
        ]);
    }

    /**
     * API endpoint untuk mendapatkan tahun yang tersedia
     */
    public function getAvailableYears()
    {
        $years = Order::selectRaw('DISTINCT YEAR(created_at) as year')
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        return response()->json([
            'years' => $years
        ]);
    }
}
