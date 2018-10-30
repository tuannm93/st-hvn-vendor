<?php

namespace App\Http\Middleware;

use Closure;
use App;

class Locale
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
        $rawLocale = session('locale');
        if (in_array($rawLocale, config('app.locales'))) {
            $locale = $rawLocale;
        } else {
            $locale = config('app.locale');
        }
        App::setLocale($locale);

        return $next($request);
    }
}
