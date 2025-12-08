<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Menampilkan halaman order dengan data lengkap
     */
    public function index()
    {
        // Load semua order dengan relasi
        $orders = Order::with(['customer', 'items.product'])
            ->latest()
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'order_id' => $order->order_id,
                    'created_at' => $order->created_at->format('d M Y'),
                    'customer' => $order->customer->name ?? 'Unknown',
                    'items_count' => $order->items->count(),
                    'total_items' => $order->total_items,
                    'total_points' => number_format($order->total_points, 0, ',', '.'),
                ];
            });

        // Load data customer dan product untuk dropdown
        $customers = Customer::select('id', 'name')->orderBy('name')->get();
        $products = Product::select('id', 'name', 'sku', 'points_per_unit', 'quantity')
            ->where('quantity', '>', 0) // Hanya produk yang ada stoknya
            ->orderBy('name')
            ->get();

        return view('order.order_page', compact('orders', 'customers', 'products'));
    }

    /**
     * Tambah order baru dengan multiple items
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.qty' => 'required|integer|min:1',
                'notes' => 'nullable|string|max:1000'
            ], [
                'customer_id.required' => 'Customer harus dipilih',
                'items.required' => 'Minimal 1 produk harus dipilih',
                'items.min' => 'Minimal 1 produk harus dipilih',
                'items.*.product_id.required' => 'Produk harus dipilih',
                'items.*.qty.required' => 'Quantity harus diisi',
                'items.*.qty.min' => 'Quantity minimal 1',
            ]);

            DB::beginTransaction();

            // Generate order_id unik
            do {
                $orderId = 'ORD-' . strtoupper(Str::random(8));
            } while (Order::where('order_id', $orderId)->exists());

            // Buat Order (header)
            $order = Order::create([
                'order_id' => $orderId,
                'customer_id' => $validated['customer_id'],
                'notes' => $validated['notes'] ?? null,
                'total_points' => 0, // Akan diupdate otomatis
                'total_items' => 0,  // Akan diupdate otomatis
            ]);

            // Loop untuk setiap item
            $itemsData = [];
            foreach ($validated['items'] as $itemData) {
                $product = Product::findOrFail($itemData['product_id']);

                // Validasi stok
                if ($itemData['qty'] > $product->quantity) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Stok produk '{$product->name}' tidak cukup. Tersisa: {$product->quantity}"
                    ], 422);
                }

                // Kurangi stok produk
                $product->decrement('quantity', $itemData['qty']);

                // Buat order item
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'sku' => $product->sku,
                    'product_name' => $product->name,
                    'qty' => $itemData['qty'],
                    'points_per_unit' => $product->points_per_unit,
                    'total_points' => $itemData['qty'] * $product->points_per_unit,
                ]);

                // Simpan untuk response
                $itemsData[] = [
                    'product_name' => $product->name,
                    'sku' => $product->sku,
                    'qty' => $itemData['qty'],
                    'points_per_unit' => number_format($product->points_per_unit, 0, ',', '.'),
                    'total_points' => number_format($orderItem->total_points, 0, ',', '.'),
                ];
            }

            // Update total_points dan total_items dari items
            $order->updateTotals();

            DB::commit();

            // Reload order untuk dapat total yang sudah terupdate
            $order->refresh();
            $order->load(['customer', 'items.product']);

            return response()->json([
                'success' => true,
                'message' => 'Order berhasil ditambahkan!',
                'data' => [
                    'id' => $order->id,
                    'order_id' => $order->order_id,
                    'created_at' => $order->created_at->format('d M Y'),
                    'date' => $order->formatted_date,
                    'customer' => $order->customer->name,
                    'items_count' => $order->items->count(),
                    'total_items' => $order->total_items,
                    'total_points' => number_format($order->total_points, 0, ',', '.'),
                    'items' => $itemsData,
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

            // Simpan error lengkap ke log Laravel
            Log::error('Order store failed: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambahkan order.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tampilkan detail order beserta items
     */
    public function show($id)
    {
        try {
            $order = Order::with(['customer', 'items.product'])
                ->findOrFail($id);

            $itemsData = $order->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product_name,
                    'sku' => $item->sku,
                    'qty' => $item->qty,
                    'points_per_unit' => $item->points_per_unit,
                    'total_points' => $item->total_points,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $order->id,
                    'order_id' => $order->order_id,
                    'customer_id' => $order->customer_id,
                    'customer_name' => $order->customer->name,
                    'notes' => $order->notes,
                    'total_points' => $order->total_points,
                    'total_items' => $order->total_items,
                    'items' => $itemsData,
                    'created_at' => $order->formatted_date,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Order show failed: ' . $e->getMessage(), [
                'order_id' => $id,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat detail order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update order (customer, notes, dan items)
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.qty' => 'required|integer|min:1',
                'notes' => 'nullable|string|max:1000'
            ]);

            DB::beginTransaction();

            $order = Order::with('items')->findOrFail($id);

            // Kembalikan stok produk lama
            foreach ($order->items as $oldItem) {
                $oldProduct = Product::find($oldItem->product_id);
                if ($oldProduct) {
                    $oldProduct->increment('quantity', $oldItem->qty);
                }
            }

            // Hapus semua items lama
            $order->items()->delete();

            // Update order header
            $order->update([
                'customer_id' => $validated['customer_id'],
                'notes' => $validated['notes'] ?? null,
            ]);

            // Tambah items baru
            foreach ($validated['items'] as $itemData) {
                $product = Product::findOrFail($itemData['product_id']);

                // Validasi stok
                if ($itemData['qty'] > $product->quantity) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Stok produk '{$product->name}' tidak cukup. Tersisa: {$product->quantity}"
                    ], 422);
                }

                // Kurangi stok
                $product->decrement('quantity', $itemData['qty']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'sku' => $product->sku,
                    'product_name' => $product->name,
                    'qty' => $itemData['qty'],
                    'points_per_unit' => $product->points_per_unit,
                    'total_points' => $itemData['qty'] * $product->points_per_unit,
                ]);
            }

            // Update total_points dan total_items
            $order->updateTotals();

            DB::commit();

            // Reload order
            $order->refresh();
            $order->load(['customer', 'items.product']);

            return response()->json([
                'success' => true,
                'message' => 'Order berhasil diupdate!',
                'data' => [
                    'id' => $order->id,
                    'order_id' => $order->order_id,
                    'created_at' => $order->created_at->format('d M Y'),
                    'date' => $order->formatted_date,
                    'customer' => $order->customer->name,
                    'items_count' => $order->items->count(),
                    'total_items' => $order->total_items,
                    'total_points' => number_format($order->total_points, 0, ',', '.'),
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

            Log::error('Order update failed: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengupdate order.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus order beserta semua items
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $order = Order::with('items')->findOrFail($id);

            // Kembalikan stok produk
            foreach ($order->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->increment('quantity', $item->qty);
                }
            }

            // Hapus order (items akan terhapus otomatis karena cascade)
            $order->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Order delete failed: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus order.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
