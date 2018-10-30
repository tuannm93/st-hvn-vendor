<?php

namespace App\Services\UploadFile;

use Illuminate\Http\UploadedFile;

class UploadFile
{
    /**
     * @var UploadedFile
     */
    protected $file;

    /**
     * @var string
     */
    protected $path;
    /**
     * @var null|string
     */
    protected $fileName;

    /**
     * UploadFile constructor.
     *
     * @param UploadedFile $file
     * @param $path
     * @param null         $fileName
     */
    public function __construct(UploadedFile $file, $path, $fileName = null)
    {
        $this->file = $file;
        $this->path = $path;
        $this->fileName = $fileName ?? $this->file->getClientOriginalName();
    }

    /**
     * @return string
     */
    private function getExtension()
    {
        return $this->file->getClientOriginalExtension();
    }

    /**
     * @return string
     */
    public function upload()
    {
        $name = $this->fileName . '.' . $this->getExtension();
        $this->file->move($this->path, $name);
        return $name;
    }


    /**
     * @return null|string
     */
    public function getOriginalName()
    {
        return $this->file->getClientOriginalName();
    }

    /**
     * @return null|string
     */
    public function getMiMeType()
    {
        return $this->file->getClientMimeType();
    }
}
