<?php

namespace App\Observers;

use App\Helpers\LogHelper;
use App\Models\OrderItem;

class OrderItemObserver
{
    public function created(OrderItem $orderItem): void
    {
        LogHelper::log('order_item', 'created', $orderItem->id, null, $orderItem->toArray());
    }

    public function updated(OrderItem $orderItem): void
    {
        LogHelper::log('order_item', 'updated', $orderItem->id, $orderItem->getOriginal(), $orderItem->getDirty());
    }

    public function deleted(OrderItem $orderItem): void
    {
        LogHelper::log('order_item', 'deleted', $orderItem->id, $orderItem->toArray(), null);
    }
}
