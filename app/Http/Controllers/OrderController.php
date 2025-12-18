<?php

namespace App\Http\Controllers;

use App\Exports\OrdersExport;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\Product;
use App\Services\OrderService;
use App\Helpers\LogHelper;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exports\OrdersWithItemsExport;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    /**
     * Menampilkan halaman order dengan data lengkap
     */
    public function index()
    {
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
                    'price' => number_format((float) $order->price, 0, ',', '.'),
                ];
            });

        $customers = Customer::select('id', 'name')->orderBy('name')->get();

        // REMOVED: where('quantity', '>', 0) - tidak ada stock tracking lagi
        $products = Product::select('id', 'name', 'sku', 'points_per_unit')
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
                'price' => 'required|numeric|min:0', // ADDED: input manual total harga
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.qty' => 'required|integer|min:1',
                'notes' => 'nullable|string|max:1000'
            ], [
                'customer_id.required' => 'Customer harus dipilih',
                'price.required' => 'Total harga pembelian harus diisi', // ADDED
                'price.numeric' => 'Total harga pembelian harus berupa angka', // ADDED
                'items.required' => 'Minimal 1 produk harus dipilih',
                'items.min' => 'Minimal 1 produk harus dipilih',
                'items.*.product_id.required' => 'Produk harus dipilih',
            ]);

            DB::beginTransaction();

            // Generate order_id unik
            do {
                $orderId = 'ORD-' . strtoupper(Str::random(8));
            } while (Order::where('order_id', $orderId)->exists());

            // Siapkan data before untuk log
            $beforeData = [];
            $afterData = [];

            // Nonaktifkan observer sementara
            Order::unsetEventDispatcher();
            OrderItem::unsetEventDispatcher();

            // Buat Order (header)
            $order = Order::create([
                'order_id' => $orderId,
                'customer_id' => $validated['customer_id'],
                'price' => $validated['price'], // ADDED
                'notes' => $validated['notes'] ?? null,
                'total_points' => 0,
                'total_items' => 0,
            ]);

            // Loop untuk setiap item
            foreach ($validated['items'] as $itemData) {
                $product = Product::findOrFail($itemData['product_id']);

                // REMOVED: Validasi stok (tidak ada stock tracking)

                // Buat order item
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'sku' => $product->sku,
                    'product_name' => $product->name,
                    'qty' => $itemData['qty'],
                    'points_per_unit' => $product->points_per_unit,
                    'total_points' => $itemData['qty'] * $product->points_per_unit,
                    // REMOVED: price_per_unit, total_price
                ]);

                // Simpan item untuk after data
                $afterData['items'][] = [
                    'product_name' => $product->name,
                    'sku' => $product->sku,
                    'qty' => $itemData['qty'],
                    'points_per_unit' => $product->points_per_unit,
                    'total_points' => $orderItem->total_points,
                ];
            }

            // Update total_points dan total_items
            OrderService::recalculate($order->id);
            $order->refresh();

            // Siapkan after data untuk order
            $afterData['order'] = [
                'order_id' => $order->order_id,
                'customer_id' => $order->customer_id,
                'price' => $order->price, // ADDED
                'notes' => $order->notes,
                'total_items' => $order->total_items,
                'total_points' => $order->total_points,
            ];

            // Buat log gabungan yang rapi
            LogHelper::log('order', 'created', $order->id, $beforeData, $afterData);

            DB::commit();

            // Load relasi untuk response
            $order->load(['customer', 'items.product']);

            // Siapkan items data untuk response
            $itemsData = $order->items->map(function ($item) {
                return [
                    'product_name' => $item->product_name,
                    'sku' => $item->sku,
                    'qty' => $item->qty,
                    'points_per_unit' => number_format($item->points_per_unit, 0, ',', '.'),
                    'total_points' => number_format($item->total_points, 0, ',', '.'),
                ];
            });

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
                    'price' => number_format((float) $order->price, 0, ',', '.'),
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
                    'price' => $order->price, // ADDED
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
                'price' => 'required|numeric|min:0', // ADDED
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.qty' => 'required|integer|min:1',
                'notes' => 'nullable|string|max:1000'
            ]);

            DB::beginTransaction();

            $order = Order::with('items')->findOrFail($id);

            // Siapkan before data
            $beforeData = [
                'order' => [
                    'order_id' => $order->order_id,
                    'customer_id' => $order->customer_id,
                    'price' => $order->price, // ADDED
                    'notes' => $order->notes,
                    'total_items' => $order->total_items,
                    'total_points' => $order->total_points,
                ],
                'items' => [],
            ];

            // Simpan before state items
            foreach ($order->items as $oldItem) {
                $beforeData['items'][] = [
                    'product_name' => $oldItem->product_name,
                    'sku' => $oldItem->sku,
                    'qty' => $oldItem->qty,
                    'total_points' => $oldItem->total_points,
                ];
            }

            // Nonaktifkan observer
            Order::unsetEventDispatcher();
            OrderItem::unsetEventDispatcher();

            // REMOVED: Kembalikan stok produk lama (tidak ada stock tracking)

            // Hapus semua items lama
            $order->items()->delete();

            // Update order header
            $order->update([
                'customer_id' => $validated['customer_id'],
                'price' => $validated['price'], // ADDED
                'notes' => $validated['notes'] ?? null,
            ]);

            // Siapkan after data
            $afterData = ['items' => []];

            // Tambah items baru
            foreach ($validated['items'] as $itemData) {
                $product = Product::findOrFail($itemData['product_id']);

                // REMOVED: Validasi stok

                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'sku' => $product->sku,
                    'product_name' => $product->name,
                    'qty' => $itemData['qty'],
                    'points_per_unit' => $product->points_per_unit,
                    'total_points' => $itemData['qty'] * $product->points_per_unit,
                ]);

                // Simpan after state item
                $afterData['items'][] = [
                    'product_name' => $product->name,
                    'sku' => $product->sku,
                    'qty' => $itemData['qty'],
                    'total_points' => $orderItem->total_points,
                ];
            }

            // Update total_points dan total_items
            OrderService::recalculate($order->id);
            $order->refresh();

            $afterData['order'] = [
                'order_id' => $order->order_id,
                'customer_id' => $order->customer_id,
                'price' => $order->price, // ADDED
                'notes' => $order->notes,
                'total_items' => $order->total_items,
                'total_points' => $order->total_points,
            ];

            // Buat log gabungan
            LogHelper::log('order', 'updated', $order->id, $beforeData, $afterData);

            DB::commit();

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
                    'price' => number_format((float) $order->price, 0, ',', '.'),
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

            // Siapkan before data untuk log
            $beforeData = [
                'order' => [
                    'order_id' => $order->order_id,
                    'customer_id' => $order->customer_id,
                    'price' => $order->price, // ADDED
                    'total_items' => $order->total_items,
                    'total_points' => $order->total_points,
                ],
                'items' => [],
            ];

            foreach ($order->items as $item) {
                $beforeData['items'][] = [
                    'product_name' => $item->product_name,
                    'sku' => $item->sku,
                    'qty' => $item->qty,
                    'total_points' => $item->total_points,
                ];
            }

            // Nonaktifkan observer
            Order::unsetEventDispatcher();
            OrderItem::unsetEventDispatcher();

            // REMOVED: Kembalikan stok produk (tidak ada stock tracking)

            // Hapus order
            $order->delete();

            // Buat log gabungan
            LogHelper::log('order', 'deleted', $id, $beforeData, []);

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

    public function exportOrdersWithItemsExcel()
    {
        return Excel::download(
            new OrdersExport(),
            'orders-complete-' . now()->format('Y-m-d') . '.xlsx'
        );
    }
}
