<?php

namespace App\Observers;

use App\Models\Customer;
use App\Helpers\LogHelper;

class CustomerObserver
{
    public function created(Customer $customer)
    {
        LogHelper::log('customer', 'created', $customer->id, null, $customer->toArray());
    }

    public function updated(Customer $customer)
    {
        $before = $customer->getOriginal();
        $after  = $customer->getDirty();

        LogHelper::log('customer', 'updated', $customer->id, $before, $after);
    }

    public function deleted(Customer $customer)
    {
        LogHelper::log('customer', 'deleted', $customer->id, $customer->toArray(), null);
    }
}
