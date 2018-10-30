<?php
namespace App\Services\Log;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class CorpLogService
{

    /**
     * @var string
     */
    private $filePath;
    /**
     * @var string
     */
    private $logName;

    /**
     * CorpLogService constructor.
     *
     * @param string $filePath
     * @param string $logName
     */
    public function __construct($filePath = 'logs/prog_import.log', $logName = 'prog_import_log')
    {
        $logPath = str_replace('.log', '-' . date('Y-m-d') . '.log', $filePath);
        $this->filePath = storage_path($logPath);
        $this->logName = $logName;
    }

    /**
     * @author thaihv
     * @param  string  $title
     * @param array   $data  data to write log
     * @param  integer $level
     * @return boolean
     * @throws \Exception
     * @throws \Exception
     */
    public function log($title, $data = [], $level = 400)
    {
        if (!is_array($data)) {
            $data = [$data];
        }
        $log = new Logger($this->logName);
        $log->pushHandler(new StreamHandler($this->filePath));
        switch ($level) {
            case Logger::INFO:
                $log->info($title, $data);
                break;
            case Logger::DEBUG:
                $log->debug($title, $data);
                break;
            case Logger::NOTICE:
                $log->notice($title, $data);
                break;
            case Logger::WARNING:
                $log->warning($title, $data);
                break;
            case Logger::CRITICAL:
                $log->critical($title, $data);
                break;
            case Logger::ALERT:
                $log->alert($title, $data);
                break;
            case Logger::EMERGENCY:
                $log->emergency($title, $data);
                break;
            default:
                $log->error($title, $data);
        }

        return true;
    }
}
