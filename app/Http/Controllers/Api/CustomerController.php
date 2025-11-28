<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Customer::query();

            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            }

            $customers = $query->latest()->paginate(20);

            return response()->json([
                'success' => true,
                'message' => 'Data customer berhasil diambil',
                'data' => $customers
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data customer',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:customers,phone',
        ], [
            'name.required' => 'Nama customer wajib diisi',
            'name.max' => 'Nama customer maksimal 255 karakter',
            'phone.required' => 'Nomor HP wajib diisi',
            'phone.unique' => 'Nomor HP sudah terdaftar',
            'phone.max' => 'Nomor HP maksimal 20 karakter',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $customer = Customer::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Customer berhasil ditambahkan',
                'data' => $customer
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan customer',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $totalPoints = $customer->transaction()->sum('points_earned');
            $totalTransactions = $customer->transaction()->count();

            return response()->json([
                'success' => true,
                'message' => 'Data customer berhasil diambil',
                'data' => [
                    'customer' => $customer,
                    'total_points' => $totalPoints,
                    'total_transactions' => $totalTransactions
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Customer tidak ditemukan',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $customer = Customer::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|string|max:255',
                'phone' => 'sometimes|string|max:20|unique:customers,phone,' . $id,
            ], [
                'name.max' => 'Nama customer maksimal 255 karakter',
                'phone.unique' => 'Nomor HP sudah terdaftar',
                'phone.max' => 'Nomor HP maksimal 20 karakter',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $customer->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Customer berhasil diupdate',
                'data' => $customer
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate customer',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $customer = Customer::findOrFail($id);

            if ($customer->transaction()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer tidak bisa dihapus karena memiliki transaksi'
                ], 422);
            }

            $customer->delete();

            return response()->json([
                'success' => true,
                'message' => 'Customer berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus customer',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function transactions($id)
    {
        try {
            $customer = Customer::findOrFail($id);

            $transactions = $customer->transaction()
                ->with('product')
                ->latest('transaction_date')
                ->paginate(20);

            return response()->json([
                'success' => true,
                'message' => 'Data transaksi customer berhasil diambil',
                'data' => $transactions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data transaksi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function totalPoints($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $totalPoints = $customer->transaction()->sum('points_earned');

            return response()->json([
                'success' => true,
                'message' => 'Total poin berhasil diambil',
                'data' => [
                    'customer_id' => $customer->id,
                    'customer_name' => $customer->name,
                    'total_points' => $totalPoints
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil total poin',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
