<?php
/**
 * Created by PhpStorm.
 * User: nguyentran
 * Date: 2/6/2018
 * Time: 7:42 PM
 */

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class DBQuery
{
    /**
     * DBQuery constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param $filename
     * @param array    $params
     * @return array
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function select($filename, $params = [])
    {
        $sql = $this->getSql($filename);

        return DB::select($sql, $params);
    }

    /**
     * @param $filename
     * @param array    $params
     * @return bool
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function statement($filename, $params = [])
    {
        $sql = $this->getSql($filename);

        return DB::statement($sql, $params);
    }

    /**
     * @param $filename
     * @param array    $params
     * @return bool
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function insert($filename, $params = [])
    {
        $sql = $this->getSql($filename);

        return DB::insert($sql, $params);
    }

    /**
     * @param $filename
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function getSql($filename)
    {
        return Storage::get("sql".DIRECTORY_SEPARATOR.$filename);
    }
}
