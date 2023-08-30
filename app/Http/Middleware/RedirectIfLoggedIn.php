<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Cookie;

use Closure;

class RedirectIfLoggedIn
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next)
    {
        $cookie_token = config('env.COOKIE_TOKEN');
        if (Cookie::has($cookie_token)) {
            $currentUser = app('App\Http\Controllers\Master\AuthController')->getAuthenticatedUser()->getData();
			if ($currentUser->code == 200) {
                app('App\Http\Controllers\Master\AuthController')->set_profile($cookie_token);
                return redirect('/');
			}
        }

        return $next($request);
    }
}
