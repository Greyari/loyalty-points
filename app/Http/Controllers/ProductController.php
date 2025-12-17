<?php

namespace App\Http\Controllers;

use App\Imports\ProductsImport;
use App\Models\Order;
use App\Models\Product;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Menampilkan halaman inventory dengan data lengkap.
     */
    public function index()
    {
        // Load semua data tanpa pagination (dipakai untuk client-side pagination)
        $products = Product::all()->map(function ($p) {
            return [
                'id'             => $p->id,
                'name'           => $p->name,
                'sku'            => $p->sku,
                'points_per_unit'=> $p->points_per_unit,
            ];
        });

        return view('inventory.inventory_page', compact('products'));
    }

    /**
     * Tambah data product
     */
    public function store(Request $request)
    {
        try {
            // Validasi input dari form
            $validated = $request->validate([
                'name'            => 'required|string|max:255',
                'sku'             => 'required|string|max:255|unique:products,sku',
                'points_per_unit' => 'required|numeric|min:0',
            ]);

            // Simpan ke database
            $product = Product::create($validated);

            // Response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Product berhasil ditambahkan!',
                'data'    => $product // cukup return model, tidak perlu manual array
            ]);

        } catch (ValidationException $e) {
            // Gabung semua pesan error menjadi 1 kalimat
            $allErrors = implode('<br>', $e->validator->errors()->all());
            return response()->json([
                'success' => false,
                'message' => $allErrors,
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambahkan product.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update product
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name'            => 'required|string|max:255',
                'sku'             => 'required|string|max:255|unique:products,sku,' . $id,
                'points_per_unit' => 'required|numeric|min:0',
            ]);

            $product = Product::findOrFail($id);
            $product->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Product berhasil diupdate!',
                'data'    => $product
            ]);

        } catch (ValidationException $e) {
            // Gabung semua pesan error menjadi 1 kalimat
            $allErrors = implode('<br>', $e->validator->errors()->all());
            return response()->json([
                'success' => false,
                'message' => $allErrors,
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengupdate product.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus product
     */
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);

            // Ambil semua order yang menggunakan product ini
            $orders = Order::whereHas('items', function($q) use ($id) {
                $q->where('product_id', $id);
            })->get();

            // Hapus product
            $product->delete();

            // Setelah product dihapus, recalc semua order yg terdampak
            foreach ($orders as $order) {

                // Hitung ulang order
                OrderService::recalculate($order->id);

                // Setelah dihitung ulang, cek apakah order masih ada item
                $order->refresh();

                if ($order->items()->count() == 0) {
                    $order->delete();
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Product deleted and affected orders updated'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saat menghapus product',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xls,xlsx,csv|max:10240',
        ]);

        try {
            Log::info('======== STARTING IMPORT ========');
            Log::info('File: ' . $request->file('excel_file')->getClientOriginalName());

            $import = new ProductsImport();
            Excel::import($import, $request->file('excel_file'));

            $stats = $import->getStats();

            Log::info('======== IMPORT COMPLETED ========', $stats);

            $message = sprintf(
                'Import completed! Imported: %d products, Skipped: %d rows',
                $stats['imported'],
                $stats['skipped']
            );

            return back()->with('success', $message);

        } catch (\Throwable $th) {
            Log::error('IMPORT PRODUCT FAILED', [
                'error' => $th->getMessage(),
                'file'  => $request->file('excel_file')->getClientOriginalName(),
                'line'  => $th->getLine(),
                'trace' => $th->getTraceAsString(),
            ]);

            return back()->with('error', 'Import failed: ' . $th->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $file = public_path('templates/product_import_example.xls');

        if (!file_exists($file)) {
            return back()->with('error', 'Example template not found.');
        }

        return response()->download($file);
    }
}
