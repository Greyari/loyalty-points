<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use App\Helpers\LogHelper;
use App\Models\User; // <-- tambahkan ini

class LogAuthActivity
{
    /**
     * @param Login $event
     */
    public function handleLogin(Login $event): void
    {
        /** @var User $user */
        $user = $event->user; 

        LogHelper::log('auth', 'login', $user->id, null, [
            'status' => 'User successfully logged in',
            'email'  => $user->email
        ]);
    }

    /**
     * @param Logout $event
     */
    public function handleLogout(Logout $event): void
    {
        /** @var User|null $user */
        $user = $event->user;

        if (!$user) {
            return;
        }

        LogHelper::log('auth', 'logout', $user->id, null, [
            'status' => 'User successfully logged out',
            'email'  => $user->email
        ]);
    }
}
