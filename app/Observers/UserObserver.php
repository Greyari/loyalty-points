<?php

namespace App\Observers;

use App\Models\User;
use App\Helpers\LogHelper;

class UserObserver
{
    public function created(User $user)
    {
        LogHelper::log('user', 'created', $user->id, null, [
            'name'  => $user->name,
            'email' => $user->email,
            'password' => '*** created ***'
        ]);
    }

    public function updated(User $user)
    {
        $before = $user->getOriginal();
        $after = $user->getDirty();

        // remove password value if it's changing
        $beforeSafe = [];
        $afterSafe  = [];

        foreach ($after as $key => $value) {

            if ($key === 'password') {
                // tandai saja bahwa password berubah
                $beforeSafe['password'] = '*** old password changed ***';
                $afterSafe['password']  = '*** new password updated ***';
                continue;
            }

            // masukin data normal (safe)
            $beforeSafe[$key] = $before[$key] ?? null;
            $afterSafe[$key]  = $value;
        }

        LogHelper::log('user', 'updated', $user->id, $beforeSafe, $afterSafe);
    }

    public function deleted(User $user)
    {
        LogHelper::log('user', 'deleted', $user->id, [
            'name'  => $user->name,
            'email' => $user->email,
        ], null);
    }
}
