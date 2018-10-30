<?php

namespace App\Http\Controllers\DailyList;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;

use Illuminate\Support\Facades\Storage;

class DailyListController extends Controller
{
    /**
     * index function call admin index view
     *
     * @return View
     */
    public function index()
    {
        $directories = Config::get('datacustom.daily_directory');
        $files = [];
        foreach ($directories as $key => $directory) {
            $files[$key] = [];
            if (Storage::disk('st_daily')->exists($directory)) {
                foreach (Storage::disk('st_daily')->allFiles($directory) as $file) {
                    $files[$key][] = [
                        "filename" => basename($file),
                        "path" => storage_path($file)
                    ];
                }
            } else {
                Storage::disk('st_daily')->makeDirectory($directory, 0775, true);
            }
            arsort($files[$key]);
        }

        return view('daily_list.index', ["files" => $files]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadFile()
    {
        $filePath = request()->get('filepath');
        $fileName = request()->get('filename');
        if (file_exists($filePath)) {
            $directories = Config::get('datacustom.daily_directory');
            foreach ($directories as $key => $directory) {
                if (Storage::disk('st_daily')->exists($directory)) {
                    foreach (Storage::disk('st_daily')->allFiles($directory) as $file) {
                        if (basename($file) == $fileName && $filePath == storage_path($file)) {
                            return response()->download($filePath, $fileName);
                        }
                    }
                }
            }
        }
        return abort('404');
    }
}
