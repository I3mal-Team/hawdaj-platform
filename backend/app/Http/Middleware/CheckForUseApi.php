<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckForUseApi
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
        if
        (
            $request->hasHeader('accept-tokenapi') &&
            $request->header('accept-tokenapi') === "k*RQ=z*2K4@WnA6d2h_&z39?bE9kDszkwh4XTePpU_vA"
        )
        {
            return $next($request);
        }
        abort(response()->json('Unauthorized', 403));

    }
}
