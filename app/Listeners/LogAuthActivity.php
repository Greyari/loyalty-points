<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use App\Helpers\LogHelper;
use App\Models\User;

class LogAuthActivity
{
    /**
     * Handle user login event
     */
    public function handleLogin(Login $event): void
    {
        /** @var User $user */
        $user = $event->user;

        // Login: hanya after, tidak ada before
        LogHelper::log(
            'auth',
            'login',
            $user->id,
            null, // before = null (tidak ada)
            [
                'auth' => [
                    'user_name' => $user->name,
                    'user_email' => $user->email,
                    'user_role' => $user->role,
                    'status' => 'Login successful'
                ]
            ]
        );
    }

    /**
     * Handle user logout event
     */
    public function handleLogout(Logout $event): void
    {
        /** @var User|null $user */
        $user = $event->user;

        if (!$user) {
            return;
        }

        // Logout: hanya after, tidak ada before
        LogHelper::log(
            'auth',
            'logout',
            $user->id,
            null, // before = null (tidak ada)
            [
                'auth' => [
                    'user_name' => $user->name,
                    'user_email' => $user->email,
                    'user_role' => $user->role,
                    'status' => 'Logged out'
                ]
            ]
        );
    }
}
