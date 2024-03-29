<?php

namespace App\Providers;

use App\Events\EmailSendEvent;
use App\Listeners\EmailSendListener;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        EmailSendEvent::class => [
            EmailSendListener::class,
        ],
    ];
}
