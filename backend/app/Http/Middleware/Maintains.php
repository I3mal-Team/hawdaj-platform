<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Maintains
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
//        if(config('setting.status') === 'close') {
//            abort(503);
//        }

    }
}
