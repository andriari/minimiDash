<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Cookie;

use Closure;
use Session;

class CheckMenu
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
        $actions = $request->route()->getAction();
        $menu = isset($actions['menu']) ? $actions['menu'] : null;
        if (!in_array($menu, Session::get('user.permission'))) {
            abort(403, 'Forbidden Access');
        }
        return $next($request);
    }
}
