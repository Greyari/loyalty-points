<?php

namespace App\Observers;

use App\Models\Product;
use App\Helpers\LogHelper;

class ProductObserver
{
    public function created(Product $product)
    {
        $afterData = [
            'product' => [
                'name' => $product->name,
                'sku' => $product->sku,
                'price' => $product->price,
                'quantity' => $product->quantity,
                'points_per_unit' => $product->points_per_unit,
            ]
        ];

        LogHelper::log('product', 'created', $product->id, [], $afterData);
    }

    public function updated(Product $product)
    {
        $original = $product->getOriginal();
        $dirty = $product->getDirty();

        // Filter out timestamps
        $dirty = array_filter($dirty, function($key) {
            return !in_array($key, ['created_at', 'updated_at']);
        }, ARRAY_FILTER_USE_KEY);

        // Jika tidak ada perubahan setelah filter timestamps, skip log
        if (empty($dirty)) {
            return;
        }

        $beforeData = ['product' => []];
        $afterData = ['product' => []];

        foreach ($dirty as $key => $value) {
            $beforeData['product'][$key] = $original[$key] ?? null;
            $afterData['product'][$key] = $value;
        }

        LogHelper::log('product', 'updated', $product->id, $beforeData, $afterData);
    }

    public function deleted(Product $product)
    {
        $beforeData = [
            'product' => [
                'name' => $product->name,
                'sku' => $product->sku,
                'price' => $product->price,
                'quantity' => $product->quantity,
                'points_per_unit' => $product->points_per_unit,
            ]
        ];

        LogHelper::log('product', 'deleted', $product->id, $beforeData, []);
    }
}
