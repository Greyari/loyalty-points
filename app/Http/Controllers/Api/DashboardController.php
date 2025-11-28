<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function summary()
    {
        try {
            $data = [
                'total_transactions' => Transaction::count(),
                'total_customers' => Customer::count(),
                'total_products' => Product::count(),
                'total_points_given' => Transaction::sum('points_earned'),
                'today_transactions' => Transaction::whereDate('transaction_date', today())->count(),
                'monthly_transactions' => Transaction::whereMonth('transaction_date', now()->month)
                    ->whereYear('transaction_date', now()->year)
                    ->count(),
            ];

            return response()->json([
                'success' => true,
                'message' => 'Data summary berhasil diambil',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data summary',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function topProducts()
    {
        try {
            $topProducts = Transaction::select(
                    'product_id',
                    DB::raw('SUM(qty) as total_qty'),
                    DB::raw('SUM(points_earned) as total_points')
                )
                ->with('product')
                ->groupBy('product_id')
                ->orderByDesc('total_qty')
                ->limit(10)
                ->get()
                ->map(function($item) {
                    return [
                        'product_id' => $item->product_id,
                        'product_name' => $item->product->name,
                        'product_sku' => $item->product->sku,
                        'product_price' => $item->product->price,
                        'total_qty_sold' => $item->total_qty,
                        'total_points_given' => $item->total_points,
                    ];
                });

            return response()->json([
                'success' => true,
                'message' => 'Data top produk berhasil diambil',
                'data' => $topProducts
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data top produk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function topCustomers()
    {
        try {
            $topCustomers = Customer::select(
                    'customers.id',
                    'customers.name',
                    'customers.phone',
                    DB::raw('COUNT(transactions.id) as total_transactions'),
                    DB::raw('SUM(transactions.points_earned) as total_points')
                )
                ->leftJoin('transactions', 'customers.id', '=', 'transactions.customer_id')
                ->groupBy('customers.id', 'customers.name', 'customers.phone')
                ->orderByDesc('total_points')
                ->limit(10)
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Data top customer berhasil diambil',
                'data' => $topCustomers
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data top customer',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function transactionChart()
    {
        try {
            $data = Transaction::select(
                    DB::raw('DATE(transaction_date) as date'),
                    DB::raw('COUNT(*) as total_transactions'),
                    DB::raw('SUM(points_earned) as total_points')
                )
                ->where('transaction_date', '>=', now()->subDays(7))
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Data chart berhasil diambil',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data chart',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
