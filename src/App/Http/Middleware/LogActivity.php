<?php

namespace Notus\LaravelLogger\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Notus\LaravelLogger\App\Http\Traits\ActivityLogger;

class LogActivity
{
    use ActivityLogger;

    /**
     * Handle an incoming request.
     *
     * @param Request  $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $description = null)
    {
        if (config('LaravelLogger.loggerMiddlewareEnabled') && $this->shouldLog($request)) {
            ActivityLogger::activity($description,json_encode($request->all()));
        }
        return $next($request);
    }

    /**
     * Determine if the request has a URI that should log.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    protected function shouldLog($request)
    {
        foreach (config('LaravelLogger.loggerMiddlewareExcept', []) as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->is($except)) {
                return false;
            }
        }

        return true;
    }
}
