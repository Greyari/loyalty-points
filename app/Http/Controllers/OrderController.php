<?php

namespace App\Http\Controllers;

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
                    'total_price' => number_format($order->total_price, 0, ',', '.'),
                ];
            });

        $customers = Customer::select('id', 'name')->orderBy('name')->get();
        $products = Product::select('id', 'name', 'sku', 'points_per_unit', 'price', 'quantity')
            ->where('quantity', '>', 0)
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

            // Siapkan data before untuk log
            $beforeData = [];
            $afterData = [];
            $productsAffected = [];

            // Nonaktifkan observer sementara
            Order::unsetEventDispatcher();
            OrderItem::unsetEventDispatcher();
            Product::unsetEventDispatcher();

            // Buat Order (header)
            $order = Order::create([
                'order_id' => $orderId,
                'customer_id' => $validated['customer_id'],
                'notes' => $validated['notes'] ?? null,
                'total_points' => 0,
                'total_items' => 0,
                'total_price' => 0,
            ]);

            // Loop untuk setiap item
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

                // Simpan before state produk
                $beforeData['products'][$product->id] = [
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'quantity' => $product->quantity,
                ];

                // Kurangi stok produk
                $product->decrement('quantity', $itemData['qty']);
                $product->refresh();

                // Simpan after state produk
                $afterData['products'][$product->id] = [
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'quantity' => $product->quantity,
                ];

                // Buat order item
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'sku' => $product->sku,
                    'product_name' => $product->name,
                    'qty' => $itemData['qty'],
                    'points_per_unit' => $product->points_per_unit,
                    'total_points' => $itemData['qty'] * $product->points_per_unit,
                    'price_per_unit' => $product->price ?? 0,
                    'total_price' => $itemData['qty'] * ($product->price ?? 0),
                ]);

                // Simpan item untuk after data
                $afterData['items'][] = [
                    'product_name' => $product->name,
                    'sku' => $product->sku,
                    'qty' => $itemData['qty'],
                    'points_per_unit' => $product->points_per_unit,
                    'total_points' => $orderItem->total_points,
                    'price_per_unit' => $product->price ?? 0,
                    'total_price' => $orderItem->total_price,
                ];
            }

            // Update total_points, total_items, dan total_price
            OrderService::recalculate($order->id);
            $order->refresh();

            // Siapkan after data untuk order
            $afterData['order'] = [
                'order_id' => $order->order_id,
                'customer_id' => $order->customer_id,
                'notes' => $order->notes,
                'total_items' => $order->total_items,
                'total_points' => $order->total_points,
                'total_price' => $order->total_price,
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
                    'price_per_unit' => number_format($item->price_per_unit, 0, ',', '.'),
                    'total_price' => number_format($item->total_price, 0, ',', '.'),
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
                    'total_price' => number_format($order->total_price, 0, ',', '.'),
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
                    'price_per_unit' => $item->price_per_unit,
                    'total_price' => $item->total_price,
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
                    'total_price' => $order->total_price,
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

            // Siapkan before data
            $beforeData = [
                'order' => [
                    'order_id' => $order->order_id,
                    'customer_id' => $order->customer_id,
                    'notes' => $order->notes,
                    'total_items' => $order->total_items,
                    'total_points' => $order->total_points,
                    'total_price' => $order->total_price,
                ],
                'items' => [],
                'products' => [],
            ];

            // Simpan before state items dan products
            foreach ($order->items as $oldItem) {
                $beforeData['items'][] = [
                    'product_name' => $oldItem->product_name,
                    'sku' => $oldItem->sku,
                    'qty' => $oldItem->qty,
                    'total_points' => $oldItem->total_points,
                    'total_price' => $oldItem->total_price,
                ];

                $oldProduct = Product::find($oldItem->product_id);
                if ($oldProduct) {
                    $beforeData['products'][$oldProduct->id] = [
                        'name' => $oldProduct->name,
                        'sku' => $oldProduct->sku,
                        'quantity' => $oldProduct->quantity,
                    ];
                }
            }

            // Nonaktifkan observer
            Order::unsetEventDispatcher();
            OrderItem::unsetEventDispatcher();
            Product::unsetEventDispatcher();

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

            // Siapkan after data
            $afterData = [
                'items' => [],
                'products' => [],
            ];

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
                $product->refresh();

                // Simpan after state produk
                $afterData['products'][$product->id] = [
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'quantity' => $product->quantity,
                ];

                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'sku' => $product->sku,
                    'product_name' => $product->name,
                    'qty' => $itemData['qty'],
                    'points_per_unit' => $product->points_per_unit,
                    'total_points' => $itemData['qty'] * $product->points_per_unit,
                    'price_per_unit' => $product->price ?? 0,
                    'total_price' => $itemData['qty'] * ($product->price ?? 0),
                ]);

                // Simpan after state item
                $afterData['items'][] = [
                    'product_name' => $product->name,
                    'sku' => $product->sku,
                    'qty' => $itemData['qty'],
                    'total_points' => $orderItem->total_points,
                    'total_price' => $orderItem->total_price,
                ];
            }

            // Update total_points, total_items, dan total_price
            OrderService::recalculate($order->id);
            $order->refresh();

            $afterData['order'] = [
                'order_id' => $order->order_id,
                'customer_id' => $order->customer_id,
                'notes' => $order->notes,
                'total_items' => $order->total_items,
                'total_points' => $order->total_points,
                'total_price' => $order->total_price,
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
                    'total_price' => number_format($order->total_price, 0, ',', '.'),
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
                    'total_items' => $order->total_items,
                    'total_points' => $order->total_points,
                    'total_price' => $order->total_price,
                ],
                'items' => [],
                'products' => [],
            ];

            foreach ($order->items as $item) {
                $beforeData['items'][] = [
                    'product_name' => $item->product_name,
                    'sku' => $item->sku,
                    'qty' => $item->qty,
                ];

                $product = Product::find($item->product_id);
                if ($product) {
                    $beforeData['products'][$product->id] = [
                        'name' => $product->name,
                        'sku' => $product->sku,
                        'quantity' => $product->quantity,
                    ];
                }
            }

            // Nonaktifkan observer
            Order::unsetEventDispatcher();
            OrderItem::unsetEventDispatcher();
            Product::unsetEventDispatcher();

            // Siapkan after data untuk products
            $afterData = ['products' => []];

            // Kembalikan stok produk
            foreach ($order->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->increment('quantity', $item->qty);
                    $product->refresh();

                    $afterData['products'][$product->id] = [
                        'name' => $product->name,
                        'sku' => $product->sku,
                        'quantity' => $product->quantity,
                    ];
                }
            }

            // Hapus order
            $order->delete();

            // Buat log gabungan
            LogHelper::log('order', 'deleted', $id, $beforeData, $afterData);

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
