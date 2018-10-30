<?php
namespace App\Services\Log;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class ShellLogService
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
     * ShellLogService constructor.
     *
     * @param string $filePath
     * @param string $logName
     */
    public function __construct($filePath = 'logs/shell.log', $logName = 'shell')
    {
        $logPath = str_replace('.log', '-' . date('Y-m-d') . '.log', $filePath);
        $this->filePath = storage_path($logPath);
        $this->logName = $logName;
    }

    /**
     * @param  string  $title
     * @param  integer $level
     * @return boolean
     * @throws \Exception
     * @throws \Exception
     */
    public function log($title, $level = 200)
    {
        $log = new Logger($this->logName);
        $log->pushHandler(new StreamHandler($this->filePath));
        switch ($level) {
            case Logger::INFO:
                $log->info($title);
                break;
            case Logger::DEBUG:
                $log->debug($title);
                break;
            case Logger::NOTICE:
                $log->notice($title);
                break;
            case Logger::WARNING:
                $log->warning($title);
                break;
            case Logger::CRITICAL:
                $log->critical($title);
                break;
            case Logger::ALERT:
                $log->alert($title);
                break;
            case Logger::EMERGENCY:
                $log->emergency($title);
                break;
            default:
                $log->error($title);
        }
        return true;
    }
}
