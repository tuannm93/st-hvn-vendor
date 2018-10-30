<?php

namespace App\Services;

use App\Repositories\DemandInfoRepositoryInterface;
use App\Repositories\MPostRepositoryInterface;
use App\Repositories\NotCorrespondItemRepositoryInterface;
use App\Repositories\NotCorrespondLogRepositoryInterface;
use App\Repositories\NotCorrespondRepositoryInterface;
use App\Services\Log\ShellLogService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CreateNotCorrespondService
{
    /**
     * @var NotCorrespondRepositoryInterface
     */
    protected $notCorrespondRepository;
    /**
     * @var DemandInfoRepositoryInterface
     */
    protected $demandInfoRepository;
    /**
     * @var NotCorrespondItemRepositoryInterface
     */
    protected $notCorrespondItemRepository;
    /**
     * @var MPostRepositoryInterface
     */
    protected $mPostRepo;
    /**
     * @var NotCorrespondLogRepositoryInterface
     */
    protected $notCorrespondLogRepository;
    /**
     * @var string
     */
    private static $user = 'system';
    /**
     * @var ShellLogService
     */
    protected $shellLogService;

    /**
     * CreateNotCorrespondService constructor.
     *
     * @param NotCorrespondRepositoryInterface     $notCorrespondRepository
     * @param DemandInfoRepositoryInterface        $demandInfoRepository
     * @param NotCorrespondItemRepositoryInterface $notCorrespondItemRepository
     * @param MPostRepositoryInterface             $mPostRepo
     * @param NotCorrespondLogRepositoryInterface  $notCorrespondLogRepository
     * @param ShellLogService                      $shellLogService
     */
    public function __construct(
        NotCorrespondRepositoryInterface $notCorrespondRepository,
        DemandInfoRepositoryInterface $demandInfoRepository,
        NotCorrespondItemRepositoryInterface $notCorrespondItemRepository,
        MPostRepositoryInterface $mPostRepo,
        NotCorrespondLogRepositoryInterface $notCorrespondLogRepository,
        ShellLogService $shellLogService
    ) {
        $this->notCorrespondRepository = $notCorrespondRepository;
        $this->demandInfoRepository = $demandInfoRepository;
        $this->notCorrespondItemRepository = $notCorrespondItemRepository;
        $this->mPostRepo = $mPostRepo;
        $this->notCorrespondLogRepository = $notCorrespondLogRepository;
        $this->shellLogService = $shellLogService;
    }


    /**
     * @throws \Exception
     */
    public function execute()
    {
        $result = true;
        $updateIds = [];
        try {
            DB::beginTransaction();
            $registeredNotCorresponds = $this->notCorrespondRepository->all();
            $setting = $this->notCorrespondItemRepository->findFirst();
            $notCorresponds = $this->demandInfoRepository->getDemandInfos($setting);
            $saveData = [];
            if (!empty($notCorresponds)) {
                foreach ($notCorresponds as $notCorrespond) {
                    if (!empty($notCorrespond['not_correspond_id'])) {
                        $saveData[] = [
                            'id' => $notCorrespond['not_correspond_id'],
                            'prefecture_cd' => $notCorrespond['address1'],
                            'jis_cd' => $notCorrespond['jis_cd'],
                            'genre_id' => $notCorrespond['genre_id'],
                            'not_correspond_count_year' => $notCorrespond['demandinfo__not_correspond_count_year'],
                            'not_correspond_count_latest' => $notCorrespond['demandinfo__not_correspond_count_latest'],
                            'import_date' => date('Y-m-d'),
                            'modified_user_id' => self::$user,
                            'modified' => date('Y-m-d H:i:s'),
                            'created' => $notCorrespond['not_correspond_created'],
                            'created_user_id' => self::$user,
                        ];
                        $updateIds[] = $notCorrespond['not_correspond_id'];
                    } else {
                        $saveData[] = [
                            'id' => null,
                            'prefecture_cd' => $notCorrespond['address1'],
                            'jis_cd' => $notCorrespond['jis_cd'],
                            'genre_id' => $notCorrespond['genre_id'],
                            'not_correspond_count_year' => $notCorrespond['demandinfo__not_correspond_count_year'],
                            'not_correspond_count_latest' => $notCorrespond['demandinfo__not_correspond_count_latest'],
                            'import_date' => date('Y-m-d'),
                            'modified_user_id' => self::$user,
                            'modified' => date('Y-m-d H:i:s'),
                            'created' => date('Y-m-d H:i:s'),
                            'created_user_id' => self::$user
                        ];
                    }
                }
                foreach ($saveData as $value) {
                    if (!empty($value['id'])) {
                        if (!$this->notCorrespondRepository->updateById($value)) {
                            $result = false;
                            break;
                        }
                        $log['not_correspond_id'] = $value['id'];
                    } else {
                        unset($value['id']);
                        $id = $this->notCorrespondRepository->insertGetId($value);
                        $log['not_correspond_id'] = $id;
                    }
                    $log['prefecture_cd'] = $value['prefecture_cd'];
                    $log['jis_cd'] = $value['jis_cd'];
                    $log['genre_id'] = $value['genre_id'];
                    $log['not_correspond_count_year'] = $value['not_correspond_count_year'];
                    $log['not_correspond_count_latest'] = $value['not_correspond_count_latest'];
                    $log['import_date'] = $value['import_date'];
                    $log['modified_user_id'] = self::$user;
                    $log['modified'] = Carbon::now();
                    $log['created'] = Carbon::now();
                    $log['created_user_id'] = self::$user;
                    $flag = $this->notCorrespondLogRepository->insert($log);
                    if (!$flag) {
                        $result = false;
                        break;
                    }
                }
            }
            if ($result) {
                $deleteData = $this->getListId($registeredNotCorresponds, $updateIds) ;
                $this->notCorrespondRepository->deleteMultiRecord($deleteData);
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollback();
            $this->shellLogService->log('登録済みエリア対応加盟店更新エラー');
            $this->shellLogService->log('登録済みエリア対応加盟店登録時に例外が発生');
        }
    }

    /**
     * @param array $registeredNotCorresponds
     * @param array $updateIds
     * @return array
     */
    private function getListId($registeredNotCorresponds, $updateIds)
    {
        $deleteData = [];
        foreach ($registeredNotCorresponds as $registeredNotCorrespond) {
            if (array_search($registeredNotCorrespond['id'], $updateIds) === false) {
                $deleteData[] = $registeredNotCorrespond['id'];
            }
        }
        return $deleteData;
    }
}
