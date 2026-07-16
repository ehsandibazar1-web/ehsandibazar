<?php

namespace App\Http\Middleware;

use Closure;

class checkSeller
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
        if(auth()->check()) {
            if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin() || auth()->user()->isSeller())
                return $next($request);
        }

        return redirect('/');
    }
}
