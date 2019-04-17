<?php

namespace App\Http\Middleware;

use Closure;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->hasHeader('X-localization')){
            // set laravel localization
            app()->setLocale($request->header('X-localization'));
        }
        // continue request
        return $next($request);
    }
}
