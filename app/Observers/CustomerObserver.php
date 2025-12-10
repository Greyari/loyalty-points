<?php

namespace App\Observers;

use App\Models\Customer;
use App\Helpers\LogHelper;

class CustomerObserver
{
    public function created(Customer $customer)
    {
        $afterData = [
            'customer' => [
                'name' => $customer->name,
                'email' => $customer->email,
                'phone' => $customer->phone,
                'address' => $customer->address,
            ]
        ];

        LogHelper::log('customer', 'created', $customer->id, [], $afterData);
    }

    public function updated(Customer $customer)
    {
        $original = $customer->getOriginal();
        $dirty = $customer->getDirty();

        // Filter out timestamps
        $dirty = array_filter($dirty, function($key) {
            return !in_array($key, ['created_at', 'updated_at']);
        }, ARRAY_FILTER_USE_KEY);

        // Jika tidak ada perubahan setelah filter timestamps, skip log
        if (empty($dirty)) {
            return;
        }

        $beforeData = ['customer' => []];
        $afterData = ['customer' => []];

        foreach ($dirty as $key => $value) {
            $beforeData['customer'][$key] = $original[$key] ?? null;
            $afterData['customer'][$key] = $value;
        }

        LogHelper::log('customer', 'updated', $customer->id, $beforeData, $afterData);
    }

    public function deleted(Customer $customer)
    {
        $beforeData = [
            'customer' => [
                'name' => $customer->name,
                'email' => $customer->email,
                'phone' => $customer->phone,
                'address' => $customer->address,
            ]
        ];

        LogHelper::log('customer', 'deleted', $customer->id, $beforeData, []);
    }
}

