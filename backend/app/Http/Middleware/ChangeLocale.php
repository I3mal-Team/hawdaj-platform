<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;

class ChangeLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->header('Locale') ?? $request->header('locale');
        if(!$locale){
            if(!Str::contains(URL::current(), 'api')){
                $locale = $request->segment(1);
            }else{
                $locale = 'ar';
            }
        }

        App::setLocale($locale);
        return $next($request);
    }
}
