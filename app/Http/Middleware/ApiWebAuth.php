<?php

namespace App\Http\Middleware;

use Closure;

class ApiWebAuth
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
        if(!$request->hasCookie('token')){
            return redirect()->route('login');
        }
        return $next($request);
    }
}
