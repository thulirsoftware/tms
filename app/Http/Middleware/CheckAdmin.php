<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
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

        if (Auth::user()->type == 'admin') {

            return $next($request);

        }
        if (Auth::user()->type === 'employee') {
            return $next($request); // allow employee
        } else {
            return redirect('/');
        }
    }
}
