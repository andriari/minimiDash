<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Cookie;

use Closure;
use Session;
use URL;

class CheckLogin
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
		$cookie_token = config('env.COOKIE_TOKEN');
		if (!Cookie::has($cookie_token)) {
			// Session::flash('error', 'Unauthorized Access');
			// Session::put('url.intended', URL::current());
			return redirect('/login');
		}

		$currentUser = app('App\Http\Controllers\Master\AuthController')->getAuthenticatedUser()->getData();
		if ($currentUser->code != 200) {
			return redirect('/login');
		}
		app('App\Http\Controllers\Master\AuthController')->set_profile($cookie_token);
		app('App\Http\Controllers\Master\NotificationController')->getNotification();

		return $next($request);
	}
}
