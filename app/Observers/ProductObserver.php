<?php

namespace App\Observers;

use App\Models\Product;
use App\Helpers\LogHelper;

class ProductObserver
{
    public function created(Product $product)
    {
        LogHelper::log('product', 'created', $product->id, null, $product->toArray());
    }

    public function updated(Product $product)
    {
        LogHelper::log('product', 'updated', $product->id, $product->getOriginal(), $product->getDirty());
    }

    public function deleted(Product $product)
    {
        LogHelper::log('product', 'deleted', $product->id, $product->toArray(), null);
    }
}
