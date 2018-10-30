<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class AuthInfosController extends Controller
{
    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function agreementLink()
    {
        return redirect()->action('Agreement\AgreementSystemController@getStep0');
    }
}
