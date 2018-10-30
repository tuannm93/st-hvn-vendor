<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use JWTAuth;
use Tymon\JWTAuth\Exceptions;

class VerifyCyzenApi
{
    const NOT_PERMISSION = 1403;
    const TOKEN_INVALID = 1404;
    const TOKEN_EXPIRED = 1405;
    const SUCCESS = 2000;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if ($user->auth != 'affiliation') {
                return response()->json(['msg' => __('auth.not_permission'), 'code' => VerifyCyzenApi::NOT_PERMISSION]);
            }
        } catch (Exception $e) {
            if ($e instanceof Exceptions\TokenInvalidException) {
                return response()->json(['msg' => __('auth.token_invalid'), 'code' => VerifyCyzenApi::TOKEN_INVALID]);
            } else {
                if ($e instanceof Exceptions\TokenExpiredException) {
                    $token = JWTAuth::getToken();
                    $new_token = JWTAuth::refresh($token);
                    return response()->json([
                        'msg' => __('auth.token_expired'),
                        'token' => $new_token,
                        'code' => 1405
                    ]);
                } else {
                    return response()->json([
                        'msg' => __('auth.unknown_error'),
                        'code' => VerifyCyzenApi::TOKEN_EXPIRED
                    ]);
                }
            }
        }
        return $next($request);
    }
}
