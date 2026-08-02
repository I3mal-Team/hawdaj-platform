<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string|null $redirectToRoute
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, $redirectToRoute = null)
    {
        if (!$request->user() ||
            ($request->user() instanceof MustVerifyEmail &&
                !$request->user()->hasVerifiedEmail())) {


            return $request->expectsJson()
                ? response()->json([
                    'message' => 'Your email address is not verified.',
                    'code' => 400,
                ], 400)
                : Redirect::route($redirectToRoute ?: 'verification.notice');
        }

        if (get_guard() == 'provider') {
            if ($request->user()->status != 'confirmed') {

                Auth::logout();

                return response()->json([
                    'message' => 'Your Account is not Active Contact to user',
                    'code' => 400,
                ], 400);
            }

        }

        return $next($request);
    }
}
