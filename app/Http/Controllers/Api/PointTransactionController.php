<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PointTransaction;
use App\Models\Product;
use Illuminate\Http\Request;

class PointTransactionController extends Controller
{
    public function index()
    {
        return response()->json(
            PointTransaction::with('product', 'user')->latest()->paginate(10)
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id'    => 'required|exists:customers,id',
            'product_id' => 'required|exists:products,id',
            'qty'        => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);

        $points = $product->points_per_unit * $request->qty;

        $data = PointTransaction::create([
            'customer_id'    => $request->customer_id,
            'order_id'   => 'ORD-' . time(),
            'product_id' => $product->id,
            'sku'        => $product->sku,
            'qty'        => $request->qty,
            'points'     => $points,
        ]);

        return response()->json($data, 201);
    }
}
