<?php

namespace App\Http\Controllers;

use App\Models\PointTransaction;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Services\MonthlySummaryService;

class PointTransactionController extends Controller
{
    /**
     * Menampilkan halaman transaksi poin dengan data lengkap.
     */
    public function index()
    {
        // Load semua data dengan relasi customer dan product
        $transactions = PointTransaction::with(['customer', 'product'])
            ->latest()
            ->get()
            ->map(function ($t) {
                return [
                    'id'            => $t->id,
                    'order_id'     => $t->order_id ?? '-',
                    'date'         => $t->created_at->timezone('Asia/Jakarta')->translatedFormat('l, d F Y (H:i)'),
                    'customer'     => $t->customer->name ?? 'Unknown',
                    'product'      => $t->product->name ?? 'Unknown',
                    'sku'          => $t->sku,
                    'qty'          => $t->qty,
                    'points'       => number_format($t->points, 0, ',', '.'),
                    'total_points' => number_format($t->qty * $t->points, 0, ',', '.'),
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
            $validated = $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'product_id'  => 'required|exists:products,id',
                'qty'         => 'required|integer|min:1'
            ]);

            $product = Product::findOrFail($validated['product_id']);

            // Validasi stok
            if ($validated['qty'] > $product->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Stok produk '{$product->name}' tidak cukup. Tersisa: {$product->quantity}"
                ], 422);
            }

            DB::beginTransaction();

            // Kurangi stok produk
            $product->decrement('quantity', $validated['qty']);

            // Generate order_id unik
            do {
                $validated['order_id'] = 'ORD-' . strtoupper(Str::random(8));
            } while (PointTransaction::where('order_id', $validated['order_id'])->exists());

            // Buat transaksi
            $transaction = PointTransaction::create([
                'customer_id' => $validated['customer_id'],
                'product_id'  => $validated['product_id'],
                'sku'         => $product->sku,
                'qty'         => $validated['qty'],
                'points'      => $product->points_per_unit,
                'order_id'    => $validated['order_id'],
            ]);

            DB::commit();

            $transaction->load(['customer', 'product']);

            // update untuk dasboard
            $summaryService = app(MonthlySummaryService::class);
            $summaryService->add($transaction);

            return response()->json([
                'success' => true,
                'message' => 'Transaction berhasil ditambahkan!',
                'data' => [
                    'id' => $transaction->id,
                    'order_id' => $transaction->order_id,
                    'date' => $transaction->created_at->format('Y-m-d H:i'),
                    'customer' => $transaction->customer->name,
                    'product' => $transaction->product->name,
                    'sku' => $transaction->sku,
                    'qty' => $transaction->qty,
                    'points' => number_format($transaction->points, 0, ',', '.'),
                    'total_points' => number_format($transaction->qty * $transaction->points, 0, ',', '.'),
                ]
            ]);

        } catch (ValidationException $e) {
            $allErrors = implode('<br>', $e->validator->errors()->all());
            return response()->json([
                'success' => false,
                'message' => $allErrors,
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambahkan transaksi.',
                'error' => $e->getMessage()
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
                'qty'         => 'required|integer|min:1'
            ]);

            DB::beginTransaction();

            $transaction = PointTransaction::findOrFail($id);

            // Simpan data lama untuk memperbaiki summary
            $oldTransaction = clone $transaction;

            // Produk lama → kembalikan stok
            $oldProduct = Product::findOrFail($transaction->product_id);
            $oldProduct->increment('quantity', $transaction->qty);

            // Produk baru
            $newProduct = Product::findOrFail($validated['product_id']);

            // Validasi stok baru
            if ($validated['qty'] > $newProduct->quantity) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => "Stok produk '{$newProduct->name}' tidak cukup. Tersisa: {$newProduct->quantity}"
                ], 422);
            }

            // Kurangi stok baru
            $newProduct->decrement('quantity', $validated['qty']);

            // Update transaksi
            $transaction->update([
                'customer_id' => $validated['customer_id'],
                'product_id'  => $validated['product_id'],
                'sku'         => $newProduct->sku,
                'qty'         => $validated['qty'],
                'points'      => $newProduct->points_per_unit,
            ]);

            DB::commit();

            // Load ulang relasi
            $transaction->load(['customer', 'product']);

            // update untuk dasboard
            $summaryService = app(MonthlySummaryService::class);
            $summaryService->subtract($oldTransaction); // hapus efek data lama
            $summaryService->add($transaction);         // tambahkan efek data baru

            // Response
            return response()->json([
                'success' => true,
                'message' => 'Transaction berhasil diupdate!',
                'data'    => [
                    'id'            => $transaction->id,
                    'order_id'      => $transaction->order_id,
                    'date'          => $transaction->created_at->format('Y-m-d H:i'),
                    'customer'      => $transaction->customer->name,
                    'product'       => $transaction->product->name,
                    'sku'           => $transaction->sku,
                    'qty'           => $transaction->qty,
                    'points'        => number_format($transaction->points, 0, ',', '.'),
                    'total_points'  => number_format($transaction->qty * $transaction->points, 0, ',', '.'),
                ]
            ]);

        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => implode('<br>', $e->validator->errors()->all()),
            ], 422);

        } catch (\Exception $e) {
            DB::rollBack();
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

            // update untuk dasboard
            $summaryService = app(MonthlySummaryService::class);
            $summaryService->subtract($transaction);

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
