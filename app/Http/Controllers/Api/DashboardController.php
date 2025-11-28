<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PointTransaction;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function summary()
    {
        // Produk paling sering dijual
        $topProducts = PointTransaction::select(
            'product_id',
            DB::raw('SUM(qty) as total_qty')
        )
        ->groupBy('product_id')
        ->with('product')
        ->orderByDesc('total_qty')
        ->take(5)
        ->get();

        // User paling banyak poin
        $topUsers = PointTransaction::select(
            'user_id',
            DB::raw('SUM(points) as total_points')
        )
        ->groupBy('user_id')
        ->with('user')
        ->orderByDesc('total_points')
        ->take(5)
        ->get();

        return response()->json([
            'top_products' => $topProducts,
            'top_users' => $topUsers
        ]);
    }
}
