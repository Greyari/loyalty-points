<?php

namespace App\Observers;

use App\Models\User;
use App\Helpers\LogHelper;

class UserObserver
{
    public function created(User $user)
    {
        $afterData = [
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'password' => '*** created ***'
            ]
        ];

        LogHelper::log('user', 'created', $user->id, [], $afterData);
    }

    public function updated(User $user)
    {
        $original = $user->getOriginal();
        $dirty = $user->getDirty();

        // Filter out timestamps
        $dirty = array_filter($dirty, function($key) {
            return !in_array($key, ['created_at', 'updated_at']);
        }, ARRAY_FILTER_USE_KEY);

        // Jika tidak ada perubahan setelah filter timestamps, skip log
        if (empty($dirty)) {
            return;
        }

        $beforeData = ['user' => []];
        $afterData = ['user' => []];

        foreach ($dirty as $key => $value) {
            if ($key === 'password') {
                $beforeData['user']['password'] = '*** old password ***';
                $afterData['user']['password'] = '*** new password ***';
            } else {
                $beforeData['user'][$key] = $original[$key] ?? null;
                $afterData['user'][$key] = $value;
            }
        }

        LogHelper::log('user', 'updated', $user->id, $beforeData, $afterData);
    }

    public function deleted(User $user)
    {
        $beforeData = [
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ]
        ];

        LogHelper::log('user', 'deleted', $user->id, $beforeData, []);
    }
}
