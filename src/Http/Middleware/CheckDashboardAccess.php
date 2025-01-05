<?php

namespace Soufian212\LaraTransManager\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Gate;

class CheckDashboardAccess
{
    /**
     * Static property to hold the custom access logic.
     * Developers can override this by extending the middleware.
     */
    protected static $accessCallback;

    /**
     * Set a custom access callback.
     */
    public static function setAccessCallback(callable $callback)
    {
        static::$accessCallback = $callback;
    }

    /**
     * Handle the incoming request.
     */
    public function handle($request, Closure $next)
    {
        // Default logic: Prevent access if the app is local
        $defaultAccess = app()->environment('local');

        // Use the custom callback if set, otherwise use the default logic
        $access = static::$accessCallback ? call_user_func(static::$accessCallback) : $defaultAccess;

        if (!$access) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}