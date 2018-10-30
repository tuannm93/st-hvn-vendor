<?php

namespace App\Services\Commission;

use App\Services\BaseService;

class CommissionFileService extends BaseService
{

    /**
     * CommissionFileService constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param integer $demandId
     * @param array $files
     * @param array $data
     * @return bool
     */
    public function uploadFile($demandId, $files, &$data)
    {
        $prefix = storage_path('upload/');
        $estimatePath = $prefix . 'estimate/';
        $receiptPath = $prefix . 'receipt/';

        if(!is_dir($prefix)){
            mkdir($prefix, 0777, true);
        }

        if(!is_dir($estimatePath)){
            mkdir($estimatePath, 0777, true);
        }

        if(!is_dir($receiptPath)){
            mkdir($receiptPath, 0777, true);
        }

        if (isset($files['DemandInfo']['jbr_estimate'])) {
            $file = $files['DemandInfo']['jbr_estimate'];
            $fileName = $file->getClientOriginalName();
            $fileId = 'estimate_' . $demandId;

            $currentFile = $this->findFileByFileId($estimatePath, $fileId);

            if ($currentFile) {
                unlink($estimatePath . $currentFile);
            }

            $fileExtension = $file->getClientOriginalExtension();

            if (!$file->move($estimatePath, $fileId . '.' . $fileExtension)) {
                return false;
            }

            $data['DemandInfo']['jbr_estimate']['name'] = $fileName;
        }

        if (isset($files['DemandInfo']['jbr_receipt'])) {
            $file = $files['DemandInfo']['jbr_receipt'];
            $fileName = $file->getClientOriginalName();
            $fileId = 'receipt_' . $demandId;

            $currentFile = $this->findFileByFileId($receiptPath, $fileId);

            if ($currentFile) {
                unlink($receiptPath . $currentFile);
            }

            $fileExtension = $file->getClientOriginalExtension();

            if (!$file->move($receiptPath, $fileId . '.' . $fileExtension)) {
                return false;
            }

            $data['DemandInfo']['jbr_receipt']['name'] = $fileName;
        }

        return true;
    }

    /**
     * @param integer $id
     * @return array
     */
    public function getFileUrl($id = null)
    {
        $estimateFileUrl = ['path' => ''];
        $receiptFileUrl = ['path' => ''];

        $prefix = storage_path('upload/');
        $estimatePath = $prefix . 'estimate/';
        $receiptPath = $prefix . 'receipt/';

        $estimateFile = $this->findFileByFileId($estimatePath, 'estimate_' . $id);
        $receiptFile = $this->findFileByFileId($receiptPath, 'receipt_' . $id);

        if (strlen($estimateFile) > 0) {
            $estimatePath = str_replace(storage_path('upload/'), '', $estimatePath);
            $estimateFileUrl['path'] = $estimatePath . $estimateFile;
        }

        if (strlen($receiptFile) > 0) {
            $receiptPath = str_replace(storage_path('upload/'), '', $receiptPath);
            $receiptFileUrl['path'] = $receiptPath . $receiptFile;
        }

        $result = ['estimate_file_url' => $estimateFileUrl, 'receipt_file_url' => $receiptFileUrl];

        return $result;
    }

    /**
     * @param string $path
     * @param integer $id
     * @return bool|string
     */
    public function findFileByFileId($path, $id)
    {
        $result = '';

        if (is_dir($path) && $dh = opendir($path)) {
            $pattern = '/^' . $id . '/';

            while (($file = readdir($dh)) != false) {
                if (preg_match($pattern, $file)) {
                    $result = $file;

                    break;
                }
            }

            closedir($dh);
        }

        return $result;
    }

    /**
     * @param string $path
     * @param string $fileName
     * @param string $newFileName
     * @param integer $targetSize
     * @return bool
     */
    public function imageResize($path, $fileName, $newFileName, $targetSize)
    {
        // PDF
        $extension = pathinfo($path . $fileName, PATHINFO_EXTENSION);

        if (strtoupper($extension) == 'PDF') {
            if (!copy($path . $fileName, $path . $newFileName)) {
                return false;
            }

            return true;
        }

        $type = exif_imagetype($path . $fileName);
        $image = $this->imageCreateAll($type, $path, $fileName);

        if (!$image) {
            return false;
        }

        $imageX = imagesx($image);
        $imageY = imagesy($image);

        // 最大サイズ 1Kとする
        $size = filesize($path . $fileName);

        if ($size > $targetSize) {
            $resizeRate = $targetSize / $size;
            $resizeImageX = sqrt($resizeRate) * $imageX * 1.25;
            $resizeImageY = sqrt($resizeRate) * $imageY * 1.25;

            $nimage = imagecreatetruecolor($resizeImageX, $resizeImageY);

            if (!$nimage) {
                imagedestroy($image);

                return false;
            }

            if (!imagecopyresampled($nimage, $image, 0, 0, 0, 0, $resizeImageX, $resizeImageY, $imageX, $imageY)) {
                imagedestroy($image);
                imagedestroy($nimage);

                return false;
            }

            if (!$this->imageWrite($type, $nimage, $path, $newFileName)) {
                imagedestroy($image);
                imagedestroy($nimage);

                return false;
            }

            imagedestroy($image);
            imagedestroy($nimage);
        } else {
            imagedestroy($image);

            if (!copy($path . $fileName, $path . $newFileName)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param integer $type
     * @param string $path
     * @param string $fileName
     * @return bool|null|resource
     */
    private function imageCreateAll($type, $path, $fileName)
    {
        switch ($type) {
            case 1:
                $image = @imageCreateFromGif($path . $fileName);

                break;
            case 2:
                $image = @imageCreateFromJpeg($path . $fileName);

                break;
            case 3:
                $image = @imageCreateFromPng($path . $fileName);

                break;
            case 6:
                $image = $this->imageCreateFromBMP($path . $fileName);

                break;
            default:
                return null;
        }

        return $image;
    }

    /**
     * @param integer $type
     * @param resource $image
     * @param string $path
     * @param string $fileName
     * @return bool
     */
    private function imageWrite($type, $image, $path, $fileName)
    {
        switch ($type) {
            case 1:
                $result = imagegif($image, $path . $fileName);

                break;
            case 2:
                $result = imagejpeg($image, $path . $fileName);

                break;
            case 3:
                $result = imagepng($image, $path . $fileName);

                break;
            case 6:
                $result = imagejpeg($image, $path . $fileName);

                break;
            default:
                return false;
        }

        return $result;
    }

    /**
     * @param $filename
     * @return bool|resource
     */
    private function imageCreateFromBMP($filename)
    {
        if (!$f1 = fopen($filename, "rb")) {
            return false;
        }

        $file = unpack("vfile_type/Vfile_size/Vreserved/Vbitmap_offset", fread($f1, 14));

        if ($file['file_type'] != 19778) {
            return false;
        }
        $bmp = $this->unPackFileToBmp($f1, $file);
        $palette = $this->getPalette($f1, $bmp);

        $img = fread($f1, $bmp['size_bitmap']);
        fclose($f1);

        $res = $this->getResolution($img, $bmp, $palette);
        return $res;
    }

    /**
     * @param string $img
     * @param array $bmp
     * @param array $palette
     * @return bool|resource
     */
    private function getResolution($img, $bmp, $palette)
    {
        $res = imagecreatetruecolor($bmp['width'], $bmp['height']);
        $pixel = 0;
        $height = $bmp['height'] - 1;
        $vide = chr(0);

        while ($height >= 0) {
            $width = 0;
            while ($width < $bmp['width']) {
                if ($bmp['bits_per_pixel'] == 24) {
                    $color = unpack("V", substr($img, $pixel, 3) . $vide);
                } elseif ($bmp['bits_per_pixel'] == 16) {
                    $color = $this->caseBmpWithBitsPerPixelEqualSixteen($img, $pixel);
                } elseif ($bmp['bits_per_pixel'] == 8) {
                    $color = unpack("n", $vide . substr($img, $pixel, 1));
                    $color[1] = $palette[$color[1] + 1];
                } elseif ($bmp['bits_per_pixel'] == 4) {
                    $color = $this->caseBmpWithBitsPerPixelEqualFour($img, $palette, $vide, $pixel);
                } elseif ($bmp['bits_per_pixel'] == 1) {
                    $color = $this->caseBmpWithBitsPerPixelEqualOne($img, $palette, $vide, $pixel);
                } else {
                    return false;
                }
                imagesetpixel($res, $width, $height, $color[1]);
                $width++;
                $pixel += $bmp['bytes_per_pixel'];
            }
            $height--;
            $pixel += $bmp['decal'];
        }
        return $res;
    }

    /**
     * @param string $img
     * @param string $pixel
     * @return array
     */
    private function caseBmpWithBitsPerPixelEqualSixteen($img, $pixel)
    {
        $color = unpack("v", substr($img, $pixel, 2));
        $blue = ($color[1] & 0x001f) << 3;
        $green = ($color[1] & 0x07e0) >> 3;
        $red = ($color[1] & 0xf800) >> 8;
        $color[1] = $red * 65536 + $green * 256 + $blue;
        return $color;
    }

    /**
     * @param string $img
     * @param array $palette
     * @param string $vide
     * @param float $pixel
     * @return array
     */
    private function caseBmpWithBitsPerPixelEqualFour($img, $palette, $vide, $pixel)
    {
        $color = unpack("n", $vide . substr($img, floor($pixel), 1));
        if (($pixel * 2) % 2 == 0) {
            $color[1] = ($color[1] >> 4);
        } else {
            $color[1] = ($color[1] & 0x0F);
        }
        $color[1] = $palette[$color[1] + 1];
        return $color;
    }

    /**
     * @param string $img
     * @param array $palette
     * @param string $vide
     * @param float $pixel
     * @return array
     */
    private function caseBmpWithBitsPerPixelEqualOne($img, $palette, $vide, $pixel)
    {
        $color = unpack("n", $vide . substr($img, floor($pixel), 1));

        if (($pixel * 8) % 8 == 0) {
            $color[1] = $color[1] >> 7;
        } elseif (($pixel * 8) % 8 == 1) {
            $color[1] = ($color[1] & 0x40) >> 6;
        } elseif (($pixel * 8) % 8 == 2) {
            $color[1] = ($color[1] & 0x20) >> 5;
        } elseif (($pixel * 8) % 8 == 3) {
            $color[1] = ($color[1] & 0x10) >> 4;
        } elseif (($pixel * 8) % 8 == 4) {
            $color[1] = ($color[1] & 0x8) >> 3;
        } elseif (($pixel * 8) % 8 == 5) {
            $color[1] = ($color[1] & 0x4) >> 2;
        } elseif (($pixel * 8) % 8 == 6) {
            $color[1] = ($color[1] & 0x2) >> 1;
        } elseif (($pixel * 8) % 8 == 7) {
            $color[1] = ($color[1] & 0x1);
        }

        $color[1] = $palette[$color[1] + 1];
        return $color;
    }

    /**
     * @param bool|resource $f1
     * @param array $file
     * @return array
     */
    private function unPackFileToBmp($f1, $file)
    {
        $bmp = unpack('Vheader_size/Vwidth/Vheight/vplanes/vbits_per_pixel' . '/Vcompression/Vsize_bitmap/Vhoriz_resolution' . '/Vvert_resolution/Vcolors_used/Vcolors_important', fread($f1, 40));
        $bmp['colors'] = pow(2, $bmp['bits_per_pixel']);

        if ($bmp['size_bitmap'] == 0) {
            $bmp['size_bitmap'] = $file['file_size'] - $file['bitmap_offset'];
        }

        $bmp['bytes_per_pixel'] = $bmp['bits_per_pixel'] / 8;
        $bmp['bytes_per_pixel2'] = ceil($bmp['bytes_per_pixel']);
        $bmp['decal'] = ($bmp['width'] * $bmp['bytes_per_pixel'] / 4);
        $bmp['decal'] -= floor($bmp['width'] * $bmp['bytes_per_pixel'] / 4);
        $bmp['decal'] = 4 - (4 * $bmp['decal']);

        if ($bmp['decal'] == 4) {
            $bmp['decal'] = 0;
        }
        return $bmp;
    }

    /**
     * @param bool|resource $f1
     * @param array $bmp
     * @return array
     */
    private function getPalette($f1, $bmp)
    {
        $palette = [];
        if ($bmp['colors'] < 65536) {
            $palette = unpack('V' . $bmp['colors'], fread($f1, $bmp['colors'] * 4));
        }
        return $palette;
    }
}
