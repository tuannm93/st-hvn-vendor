<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception               $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($this->isHttpException($exception)) {
            $statusCode = $exception->getStatusCode();
            switch ($statusCode) {
                case 400: // Bad Request
                    return redirect()->route('errors.400');
                    break;
                case 401: // Unauthorized
                    return redirect()->route('errors.401');
                    break;
                case 404: // Not Found
                    return redirect()->route('errors.404');
                    break;
                case 500: // Internal Server Error
                    return redirect()->route('errors.500');
                    break;
                case 502: // Bad Gateway
                    return redirect()->route('errors.502');
                case 503: // Service Unavailable
                    return redirect()->route('errors.503');
                    break;
            }
        }
        return parent::render($request, $exception);
    }
}
