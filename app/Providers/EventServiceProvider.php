<?php

namespace App\Providers;

use Illuminate\Auth\Events\Authenticated;
use App\Listeners\SyncSessionCartToDatabase;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Authenticated::class => [
            SyncSessionCartToDatabase::class,
        ],
    ];
}
