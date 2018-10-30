<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckCorpIdAff
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()->auth == 'affiliation' && $request->segment(2) == 'corptargetarea') {
            if (Auth::user()->affiliation_id == $request->route('id')) {
                return $next($request);
            } else {
                return response()->view('errors.401');
            }
        }
        return $next($request);
    }
}
