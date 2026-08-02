<?php

namespace App\Http\Middleware;

use Closure;

class CheckGuard
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);

//        if (auth()->user()->hasRole('guard')) {
//            return redirect(url('/dashboard/guard'));
//        }



    }
}
