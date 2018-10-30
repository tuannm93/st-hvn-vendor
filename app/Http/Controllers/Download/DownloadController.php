<?php

namespace App\Http\Controllers\Download;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DownloadController extends Controller
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * construct function
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct();
        $this->request = $request;
    }

    /**
     * download file function
     *
     * @param  string   $target
     * @param  $fileName
     * @return file
     */
    public function index($target, $fileName)
    {
        $filePath = storage_path('upload/');
        $target = base64_decode($target);
        $fileName = base64_decode($fileName);
        $filePath = $filePath . $target;

        if (file_exists($filePath)) {
            return response()->download($filePath, $fileName);
        } else {
            $this->request->session()->flash('error_file', trans('download.error_file'));
            return back();
        }
    }
}
