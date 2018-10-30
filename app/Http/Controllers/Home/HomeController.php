<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Auth;

class HomeController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function index()
    {
        $userLogin = Auth::user();
        if (is_null($userLogin) || empty($userLogin)) {
            return redirect('login');
        }

        return view('home.index');
    }
}
