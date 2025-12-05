<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CustomerController extends Controller
{
    public function index()
    {
        // Ambil semua customer dengan total poin dari transaksi
        $customers = Customer::withSum('transactions as total_points', 'points')
            ->get()
            ->map(function ($p) {
                return [
                    'id'             => $p->id,
                    'name'           => $p->name,
                    'phone'          => $p->phone,
                    'points'         => $p->total_points ?? 0,
                ];
            });

        return view('customer.customer_page', compact('customers'));
    }

    /**
     * Tambah data customer
     */
    public function store(Request $request)
    {
        try {
            // Validasi input dari form
            $validated = $request->validate([
                'name'            => 'required|string|max:255',
                'phone'            => 'required|string|max:255',
            ]);

            // Simpan ke database
            $customer = Customer::create($validated);

            // Response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Customer berhasil ditambahkan!',
                'data'    => $customer // cukup return model, tidak perlu manual array
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
                'message' => 'Terjadi kesalahan saat menambahkan customer.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update customer
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name'            => 'required|string|max:255',
                'phone'            => 'required|string|max:255',
            ]);

            $customer = Customer::findOrFail($id);
            $customer->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Customer berhasil diupdate!',
                'data'    => $customer
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
                'message' => 'Terjadi kesalahan saat mengupdate customer.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus customer
     */
    public function destroy($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $customer->delete();

            return response()->json([
                'success' => true,
                'message' => 'customer berhasil dihapus!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus customer.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
