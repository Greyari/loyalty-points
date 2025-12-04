<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
                'quantity'       => $p->quantity,
                'price'          => 'Rp ' . number_format($p->price, 0, ',', '.'),
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
                'quantity'        => 'required|integer|min:0',
                'price'           => 'required|numeric|min:0',
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
                'quantity'        => 'required|integer|min:0',
                'price'           => 'required|numeric|min:0',
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
            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product berhasil dihapus!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus product.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
