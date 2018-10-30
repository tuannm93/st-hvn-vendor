<?php

namespace App\Console\Commands\Cyzen;

use App\Services\Cyzen\CyzenUserGroupServices;
use App\Services\Cyzen\CyzenUserServices;
use Illuminate\Console\Command;

class CyzenUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:cyzen_users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @var CyzenUserServices $cyzenUser
     */
    protected $cyzenUser;
    /**
     * @var CyzenUserGroupServices $cyzenUserGroup
     */
    protected $cyzenUserGroup;

    /**
     * CyzenUsers constructor.
     * @param CyzenUserServices $cyzenUser
     */
    public function __construct(
        CyzenUserServices $cyzenUser
    ) {
        parent::__construct();
        $this->cyzenUser = $cyzenUser;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        $this->cyzenUser->handle();
    }
}
