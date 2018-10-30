<?php

namespace App\Services;

use Mockery\Exception;
use TCPDF;

class PdfGenerator
{
    /**
     * PdfGenerator constructor.
     */
    public function __construct()
    {
    }

    /**
     * commission
     */
    public function commission()
    {
        try {
            new TCPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        } catch (Exception $e) {
            Log::error($e);
        }
    }
}
