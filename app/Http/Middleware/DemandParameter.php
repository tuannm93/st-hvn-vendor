<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Controllers\Controller;

class DemandParameter extends Controller
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
        $actionName = $request->route()->getAction();
        if (!strpos($actionName['controller'], 'DemandController') && !strpos($actionName['controller'], 'DemandListController')) {
            $request->session()->forget(Controller::$sessionKeyForDemandParameter);
            $request->session()->forget(Controller::$sessionKeyForDemandSearch);
        }
        return $next($request);
    }
}
