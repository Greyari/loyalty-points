<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use App\Listeners\LogAuthActivity;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        // 'App\Events\EventName' => ['App\Listeners\EventListener'],

        Login::class => [
            LogAuthActivity::class . '@handleLogin',
        ],

        Logout::class => [
            LogAuthActivity::class . '@handleLogout',
        ],
    ];

    public function boot(): void
    {
        //
    }
}
