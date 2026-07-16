<?php

namespace App\Http\Middleware;

use Closure;

class CheckAdmin
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

        /* Start Of Set Header Parameter For Browser Cache Weakness Bug */
//        $response = $next($request);
//        $response->headers->set("Cache-Control","no-cache,no-store,max-age=0,must-revalidate"); //HTTP 1.1
//        $response->headers->set("Pragma","no-cache"); //HTTP 1.0
//        $response->headers->set("Expires","Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
        /* End Of Set Header Parameter For Browser Cache Weakness Bug */



        if(auth()->check()) {
            if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin() || auth()->user()->isOperator())
                return $next($request);
        }

        return redirect('/');
    }
}
