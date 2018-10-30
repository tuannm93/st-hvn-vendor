<?php
/**
 * Created by PhpStorm.
 * User: haonguyenh2
 * Date: 3/26/2018
 * Time: 1:55 PM
 */

namespace App\Http\Controllers;

class ErrorHandlerController extends Controller
{
    /*
     *  Bad Request Page
     */
    public function error400()
    {
        return view('errors.400');
    }

    /*
     *  Unauthorized Page
     */
    public function error401()
    {
        return view('errors.401');
    }

    /*
     *  Not Found Page
     */
    public function error404()
    {
        return view('errors.404');
    }

    /*
     * Internal Error Server Page
     */
    public function error500()
    {
        return view('errors.500');
    }

    /*
     * Bad Gateway Page
     */
    public function error502()
    {
        return view('errors.502');
    }

    /*
     * Service Unavailable Page
     */
    public function error503()
    {
        return view('errors.503');
    }
}
