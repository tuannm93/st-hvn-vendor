<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * index function call admin index view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $auth = Auth::user()->auth;
        return view('admin.index', compact('auth'));
    }
}
