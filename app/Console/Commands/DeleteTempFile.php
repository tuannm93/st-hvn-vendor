<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class DeleteTempFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:delete_temp_file';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete file in temp folder after 24h upload';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Read file in folder temp file and delete file if file created over 24h.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $expireTime = Carbon::now()->subDays(1)->timestamp;
            $tempDirectoryPath = storage_path(config('cron.delete_temp_file.path'));
            $tempFiles = File::allFiles($tempDirectoryPath);
            $overDateFiles = [];
            foreach ($tempFiles as $file) {
                $modifyTime = File::lastModified($file);
                if ($modifyTime < $expireTime) {
                    $overDateFiles[] = $file;
                }
            }
            File::delete($overDateFiles);
            return Log::info('Delete '.count($overDateFiles).' temp file success at '.Carbon::now()->format('Y-m-d H:s:i'));
        } catch (\Exception $exception) {
            return Log::error('Delete temp file error at '.Carbon::now()->format('Y-m-d H:s:i').' with message: '.$exception->getMessage());
        }
    }
}
