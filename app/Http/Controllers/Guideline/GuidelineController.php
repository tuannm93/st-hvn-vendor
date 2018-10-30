<?php

namespace App\Http\Controllers\Guideline;

use Illuminate\Routing\Controller;

class GuidelineController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('guideline.index');
    }
}
