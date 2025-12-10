<?php

namespace App\Observers;

use App\Models\Order;
use App\Helpers\LogHelper;

class OrderObserver
{
    public function created(Order $order)
    {
        LogHelper::log('order', 'created', $order->id, null, $order->toArray());
    }

    public function updated(Order $order)
    {
        LogHelper::log('order', 'updated', $order->id, $order->getOriginal(), $order->getDirty());
    }

    public function deleted(Order $order)
    {
        LogHelper::log('order', 'deleted', $order->id, $order->toArray(), null);
    }
}
