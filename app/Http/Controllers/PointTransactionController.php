<?php

namespace App\Http\Controllers;

use App\Models\PointTransaction;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class PointTransactionController extends Controller
{
    /**
     * Menampilkan halaman transaksi poin dengan data lengkap.
     */
    public function index()
    {
        // Load semua data dengan relasi customer dan product
        $transactions = PointTransaction::with(['customer', 'product'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($t) {
                return [
                    'id'            => $t->id,
                    'order_id'      => $t->order_id ?? '-',
                    'date'          => $t->created_at->format('Y-m-d H:i'),
                    'customer'      => $t->customer->name ?? 'Unknown',
                    'customer_id'   => $t->customer_id,
                    'product'       => $t->product->name ?? 'Unknown',
                    'product_id'    => $t->product_id,
                    'sku'           => $t->sku,
                    'qty'           => $t->qty,
                    'points'        => number_format($t->points, 0, ',', '.'),
                    'total_points'  => number_format($t->qty * $t->points, 0, ',', '.'),
                ];
            });

        // Load data customer dan product untuk dropdown
        $customers = Customer::select('id', 'name')->orderBy('name')->get();
        $products = Product::select('id', 'name', 'sku', 'points_per_unit')->orderBy('name')->get();

        return view('transaction.transaction_page', compact('transactions', 'customers', 'products'));
    }

    /**
     * Tambah transaksi poin
     */
    public function store(Request $request)
    {
        try {
            // Validasi input dari form
            $validated = $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'product_id'  => 'required|exists:products,id',
                'qty'         => 'required|integer|min:1',
                'order_id'    => 'nullable|string|max:100',
            ]);

            // Ambil data product untuk mendapatkan SKU dan points
            $product = Product::findOrFail($validated['product_id']);

            // Generate order_id jika tidak ada
            if (empty($validated['order_id'])) {
                $validated['order_id'] = 'ORD-' . strtoupper(Str::random(8));
            }

            // Buat transaksi
            $transaction = PointTransaction::create([
                'customer_id' => $validated['customer_id'],
                'product_id'  => $validated['product_id'],
                'sku'         => $product->sku,
                'qty'         => $validated['qty'],
                'points'      => $product->points_per_unit,
                'order_id'    => $validated['order_id'],
            ]);

            // Update total poin customer
            $customer = Customer::find($validated['customer_id']);
            $customer->increment('total_points', $product->points_per_unit * $validated['qty']);

            // Load relasi untuk response
            $transaction->load(['customer', 'product']);

            // Format response
            $responseData = [
                'id'            => $transaction->id,
                'order_id'      => $transaction->order_id,
                'date'          => $transaction->created_at->format('Y-m-d H:i'),
                'customer'      => $transaction->customer->name,
                'customer_id'   => $transaction->customer_id,
                'product'       => $transaction->product->name,
                'product_id'    => $transaction->product_id,
                'sku'           => $transaction->sku,
                'qty'           => $transaction->qty,
                'points'        => number_format($transaction->points, 0, ',', '.'),
                'total_points'  => number_format($transaction->qty * $transaction->points, 0, ',', '.'),
            ];

            return response()->json([
                'success' => true,
                'message' => 'Transaction berhasil ditambahkan!',
                'data'    => $responseData
            ]);

        } catch (ValidationException $e) {
            $allErrors = implode('<br>', $e->validator->errors()->all());
            return response()->json([
                'success' => false,
                'message' => $allErrors,
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambahkan transaksi.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update transaksi poin
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'product_id'  => 'required|exists:products,id',
                'qty'         => 'required|integer|min:1',
                'order_id'    => 'nullable|string|max:100',
            ]);

            $transaction = PointTransaction::findOrFail($id);

            // Hitung selisih poin untuk update customer
            $oldTotalPoints = $transaction->qty * $transaction->points;

            // Ambil product baru
            $product = Product::findOrFail($validated['product_id']);
            $newTotalPoints = $validated['qty'] * $product->points_per_unit;

            // Update poin customer lama (kurangi poin lama)
            if ($transaction->customer_id != $validated['customer_id']) {
                // Jika customer berubah
                $oldCustomer = Customer::find($transaction->customer_id);
                $oldCustomer->decrement('total_points', $oldTotalPoints);

                $newCustomer = Customer::find($validated['customer_id']);
                $newCustomer->increment('total_points', $newTotalPoints);
            } else {
                // Customer sama, update selisih
                $customer = Customer::find($transaction->customer_id);
                $pointDiff = $newTotalPoints - $oldTotalPoints;

                if ($pointDiff > 0) {
                    $customer->increment('total_points', $pointDiff);
                } elseif ($pointDiff < 0) {
                    $customer->decrement('total_points', abs($pointDiff));
                }
            }

            // Update transaksi
            $transaction->update([
                'customer_id' => $validated['customer_id'],
                'product_id'  => $validated['product_id'],
                'sku'         => $product->sku,
                'qty'         => $validated['qty'],
                'points'      => $product->points_per_unit,
                'order_id'    => $validated['order_id'] ?? $transaction->order_id,
            ]);

            $transaction->load(['customer', 'product']);

            $responseData = [
                'id'            => $transaction->id,
                'order_id'      => $transaction->order_id,
                'date'          => $transaction->created_at->format('Y-m-d H:i'),
                'customer'      => $transaction->customer->name,
                'customer_id'   => $transaction->customer_id,
                'product'       => $transaction->product->name,
                'product_id'    => $transaction->product_id,
                'sku'           => $transaction->sku,
                'qty'           => $transaction->qty,
                'points'        => number_format($transaction->points, 0, ',', '.'),
                'total_points'  => number_format($transaction->qty * $transaction->points, 0, ',', '.'),
            ];

            return response()->json([
                'success' => true,
                'message' => 'Transaction berhasil diupdate!',
                'data'    => $responseData
            ]);

        } catch (ValidationException $e) {
            $allErrors = implode('<br>', $e->validator->errors()->all());
            return response()->json([
                'success' => false,
                'message' => $allErrors,
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengupdate transaksi.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus transaksi poin
     */
    public function destroy($id)
    {
        try {
            $transaction = PointTransaction::findOrFail($id);

            // Kurangi poin customer
            $customer = Customer::find($transaction->customer_id);
            $totalPoints = $transaction->qty * $transaction->points;
            $customer->decrement('total_points', $totalPoints);

            $transaction->delete();

            return response()->json([
                'success' => true,
                'message' => 'Transaction berhasil dihapus!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus transaksi.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
