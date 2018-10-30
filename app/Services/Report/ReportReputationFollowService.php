<?php

namespace App\Services\Report;

use App\Repositories\MCorpRepositoryInterface;
use App\Repositories\ReputationCheckRepositoryInterface;
use App\Services\BaseService;
use App\Services\ExportService;
use DB;

class ReportReputationFollowService extends BaseService
{
    /**
     * @var array
     */
    public static $csvFormatReputationChecks = [
        'id' => '企業ID',
        'official_corp_name' => '正式企業名',
        'corp_name_kana' => '企業名ふりがな',
        'last_reputation_date' => '風評チェック更新日時（前回）',
        'commission_dial' => '取次用ダイヤル'
    ];
    /**
     * @var MCorpRepositoryInterface
     */
    protected $mCorps;
    /**
     * @var ReputationCheckRepositoryInterface
     */
    protected $reputationChecks;
    /**
     * @var ExportService
     */
    protected $exportService;

    /**
     * ReportReputationFollowService constructor.
     * @param MCorpRepositoryInterface $mCorpRepository
     * @param ReputationCheckRepositoryInterface $reputationCheckRepository
     * @param ExportService $exportService
     */
    public function __construct(
        MCorpRepositoryInterface $mCorpRepository,
        ReputationCheckRepositoryInterface $reputationCheckRepository,
        ExportService $exportService
    ) {
        $this->mCorps = $mCorpRepository;
        $this->reputationChecks = $reputationCheckRepository;
        $this->exportService = $exportService;
    }

    /**
     * prepare data for render view
     *
     * @return mixed
     */
    public function prepareListDataForView()
    {
        $result = $this->reputationChecks->getListCorpReport();
        $bAllowUpdate = false;
        $role = \Auth::user()->auth;
        if (in_array($role, ['system', 'admin', 'accounting_admin'])) {
            $bAllowUpdate = true;
        }
        $result['bAllowShowUpdate'] = $bAllowUpdate;
        return $result;
    }

    /**
     * get next or previous data for pagination
     *
     * @param integer $page
     * @return mixed
     */
    public function getDataForPagination($page)
    {
        $result = $this->reputationChecks->getListCorpReport($page);
        $bAllowUpdate = false;
        $role = \Auth::user()->auth;
        if (in_array($role, ['system', 'admin', 'accounting_admin'])) {
            $bAllowUpdate = true;
        }
        $result['bAllowShowUpdate'] = $bAllowUpdate;
        return $result;
    }

    /**
     * render file csv to download
     *
     * @return \Maatwebsite\Excel\LaravelExcelWriter|null
     */
    public function getDataForDownloadCsv()
    {
        $result = $this->reputationChecks->getListCorpReportDownload();
        $columnKeys = ReportReputationFollowService::$csvFormatReputationChecks;
        $fileName = 'reputation_follow_' . \Auth::user()->user_id;
        return $this->exportService->exportCsv($fileName, $columnKeys, $result);
    }

    /**
     * update time or insert data
     *
     * @param array $listId
     * @return boolean
     */
    public function updateReputationFollowTime($listId)
    {
        try {
            DB::beginTransaction();
            foreach ($listId as $id) {
                if (!is_null($id) && strlen(trim($id)) > 0) {
                    $this->reputationChecks->updateDateTime((int)$id);
                }
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }
}
