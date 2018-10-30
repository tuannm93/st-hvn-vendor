<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @param  string|null              $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            $data = $request->all();
            if (isset($data['user_id']) && isset($data['password'])) {
                Auth::logout();
                return redirect($request->fullUrl());
            }
            return redirect('/');
        }

        return $next($request);
    }
}
