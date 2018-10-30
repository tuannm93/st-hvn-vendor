<?php
namespace App\Services;

use Exception;
use Excel;

class ExportService extends BaseService
{
    /**
     * Export Csv
     *
     * Suggest export max 10000 rows
     *
     * @param  string $fileName
     * @param  array  $columns
     * @param  array  $rowList
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportCsv($fileName, $columns, $rowList)
    {
        $fileName .= '.csv';
        try {
            $headers = [
                'Content-type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename=' . $fileName,
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0'
            ];
            
            $callback = function () use ($columns, $rowList) {
                $file = fopen('php://output', 'w');
                fputs($file, "\xEF\xBB\xBF");
                fputcsv($file, $columns);

                foreach (array_keys($rowList) as $kData) {
                    $data = [];
                    foreach (array_keys($columns) as $kCol) {
                        if (in_array($kCol, array_keys($rowList[$kData]))) {
                            $data[] = $rowList[$kData][$kCol];
                        }
                    }
                    fputcsv($file, $data);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (Exception $e) {
            logger(__METHOD__ . ': ' . $e->getMessage());
            abort('500');
        }
    }

    /**
     * Generate CSV file
     *
     * Suggest export max 10000 rows
     *
     * @param string $fileName
     * @param array  $columns
     * @param array  $rowList
     */
    public function generateCsv($fileName, $columns, $rowList)
    {
        try {
            $fileName .= '.csv';
            $file = fopen($fileName, 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, $columns);

            foreach (array_keys($rowList) as $kData) {
                $data = [];
                foreach (array_keys($columns) as $kCol) {
                    if (in_array($kCol, array_keys($rowList[$kData]))) {
                        $data[] = $rowList[$kData][$kCol];
                    }
                }
                fputcsv($file, $data);
            }
            fclose($file);
        } catch (Exception $e) {
            logger(__METHOD__ . ': ' . $e->getMessage());
            abort('500');
        }
    }

    /**
     * Export Excel
     *
     * @param  $fileName
     * @param  $columns
     * @param  $rowList
     * @return mixed
     */
    public function exportExcel($fileName, $columns, $rowList)
    {
        try {
            $fileName = storage_path('app' . \Config::get('cron.delete_temp_file.path')) . '/' .$fileName;
            $this->generateCsv($fileName, $columns, $rowList);
            Excel::load($fileName)->convert('xls');
        } catch (\Exception $e) {
            logger(__METHOD__ . ': ' . $e->getMessage());
            abort('500');
        }
    }
}
