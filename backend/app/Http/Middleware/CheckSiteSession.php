<?php

namespace App\Http\Middleware;

use Closure;

class CheckSiteSession
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
//        if ((
//            session()->has('site_id')
//            && auth()->user()->sites->contains('id', session('site_id')))
//            || auth()->id() == 1
//        ) {
//        }
//
//        return redirect(url('/dashboard/check-site'))->with([
//            'status' => 'error',
//            'message' => __('dashboard.select_site_first')
//        ]);

    }
}
