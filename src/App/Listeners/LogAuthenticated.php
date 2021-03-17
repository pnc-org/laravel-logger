<?php

namespace pncOrg\LaravelLogger\App\Listeners;

use Illuminate\Auth\Events\Authenticated;
use pncOrg\LaravelLogger\App\Http\Traits\ActivityLogger;

class LogAuthenticated
{
    use ActivityLogger;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle ANY authenticated event.
     *
     * @param Authenticated $event
     *
     * @return void
     */
    public function handle(Authenticated $event)
    {
        if (config('LaravelLogger.logAllAuthEvents')) {
            ActivityLogger::activity('Authenticated Activity');
        }
    }
}
