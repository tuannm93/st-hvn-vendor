<?php

namespace App\Http\Controllers;

class BaseController extends Controller
{
    /**
     * @param $viewName
     * @param $data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function renderView($viewName, $data)
    {
        return view($viewName, $data);
    }
}
