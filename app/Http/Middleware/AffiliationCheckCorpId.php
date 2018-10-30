<?php


namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;

class AffiliationCheckCorpId
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        $corpId = null;
        $excludeActions = ['updateStatusMCorpCategory'];

        if (!in_array($request->route()->getActionName(), $excludeActions)) {
            if (!empty($request->route()->parameter('id'))) {
                $corpId = $request->route()->parameter('id');
            } elseif (!empty($request->route()->parameter('corpId'))) {
                $corpId = $request->route()->parameter('corpId');
            }
            if (Auth::user()->auth == 'affiliation'
                && Auth::user()->affiliation_id != $corpId
            ) {
                abort(401);
            }
        }

        return $next($request);
    }
}
