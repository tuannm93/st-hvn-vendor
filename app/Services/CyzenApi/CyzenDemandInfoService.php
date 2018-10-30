<?php

namespace App\Services\CyzenApi;

use App\Http\Controllers\Api\CyzenApiController;
use App\Repositories\CyzenDemandInfoRepositoryInterface;
use App\Repositories\DemandNotificationRepositoryInterface;
use App\Services\BaseService;
use App\Services\Cyzen\CyzenNotificationServices;

class CyzenDemandInfoService extends BaseService
{
    /**
     * @var CyzenDemandInfoRepositoryInterface $cyzenDemandInfoRepository
     */
    protected $cyzenDemandInfoRepository;

    /**
     * @var \App\Repositories\DemandNotificationRepositoryInterface $demandNotificationRepository
     */
    protected $demandNotificationRepository;

    /**
     * @var \App\Services\Cyzen\CyzenNotificationServices $cyzenNotificationService
     */
    protected $cyzenNotificationService;

    /**
     * CyzenDemandInfoService constructor.
     *
     * @param \App\Repositories\CyzenDemandInfoRepositoryInterface $cyzenDemandInfoRepository
     * @param \App\Repositories\DemandNotificationRepositoryInterface $demandNotificationRepository
     * @param \App\Services\Cyzen\CyzenNotificationServices $cyzenNotificationServices
     */
    public function __construct(
        CyzenDemandInfoRepositoryInterface $cyzenDemandInfoRepository,
        DemandNotificationRepositoryInterface $demandNotificationRepository,
        CyzenNotificationServices $cyzenNotificationServices
    ) {
        $this->cyzenDemandInfoRepository = $cyzenDemandInfoRepository;
        $this->demandNotificationRepository = $demandNotificationRepository;
        $this->cyzenNotificationService = $cyzenNotificationServices;
    }

    /**
     * @param $id
     * @param $userId
     * @return mixed
     * @throws \Exception
     */
    public function getDemandInfo($id, $userId)
    {
        try {
            $demandInfo = $this->cyzenDemandInfoRepository->apiDemandInfo($id, $userId);
            return $demandInfo;
        } catch (\Exception $e) {
            throw  new \Exception($e->getMessage());
        }
    }

    /**
     * @param $demandId
     * @param $staffId
     * @param $status
     * @return mixed
     */
    public function updateDemandStatus($demandId, $staffId, $status)
    {
        $updateDemandNotification = $this->demandNotificationRepository->updateStatusByStaffAndDemand(
            $demandId,
            $staffId,
            $status
        );

        if ($updateDemandNotification == CyzenApiController::API_SUCCESS) {
            $updated = $this->cyzenDemandInfoRepository->updateStatusCommissionInfo($demandId, $status, $staffId);
            if ($updated) {
                return CyzenApiController::API_SUCCESS;
            }
        }
        return $updateDemandNotification;
    }

    /**
     * @param $data
     * @return array
     */
    public function initApiReturn($data)
    {
        $address = trans('rits_config.' . config('rits.prefecture_div.' . $data->address1)) . " " . $data->address2;
        $state = $this->cyzenDemandInfoRepository->getDemandStatus($data->id, $data->user->user_id);
        $message = $this->getStatusMessage($data->id, $data->user->user_id);
        $site = $data->site_name;
        if (strpos(trim($data->site_name), 'http') !== 0) {
            $site = 'http://' . $data->site_name;
        }
        return [
            'id' => $data->id,
            'demand_name' => $data->id, // spot_code
            'customer_name' => $data->customer_name, // customer_name
            'customer_phone' => $data->customer_tel, //customer_tel
            'customer_address' => $address . ' ' . $data->address3, // address
            'customer_lat' => $data->lat,
            'customer_lng' => $data->lng,
            'customer_email' => $data->customer_mailaddress,// customer_mailaddress
            'genre_id' => $data->genre_id, // genre_id
            'genre_name' => $data->genre_name, //m_genres table
            'category_id' => $data->category_id, // category_id
            'category_name' => $data->category_name, //m_categories table
            'time_start' => $data->start, //visit_time_from
            'time_end' => $data->end, //visit_time_to
            'state' => $state,
            'staff_name' => $data->user->user_name,
            'staff_id' => $data->user->user_id,
            'spot_id' => $data->spot_id, //Waiting
            'site_name' => $site,
            'message' => $message,
            'location' => $address . ' ' . $data->address3
        ];
    }

    /**
     * @param $demandId
     * @param $userId
     * @return array string
     */
    public function getStatusMessage($demandId, $userId)
    {
        $status = $this->cyzenDemandInfoRepository->getNotificationStatus($demandId, $userId);
        switch ($status) {
            case CyzenNotificationServices::STATUS_BEFORE_START_WORK:
                $message = trans('cyzen_notifications.message_detail_before_start_work', ['id' => $demandId]);
                break;
            case CyzenNotificationServices::STATUS_START_TIME:
                $message = trans('cyzen_notifications.message_detail_on_start_work', ['id' => $demandId]);
                break;
            case CyzenNotificationServices::STATUS_AFTER_START_WORK:
            case CyzenNotificationServices::STATUS_DELAY_START_TIME:
                $message = trans('cyzen_notifications.message_detail_delay_start_work', ['id' => $demandId]);
                break;
            case CyzenNotificationServices::STATUS_END_TIME:
            case CyzenNotificationServices::STATUS_DELAY_END_TIME:
                $message = trans('cyzen_notifications.message_detail_on_end_work', ['id' => $demandId]);
                break;
            default:
                $message = '';
        }
        return $message;
    }

    /**
     * @param $data
     * @param $status
     * @return void
     */
    public function sendMail($data, $status)
    {
        $this->cyzenNotificationService->executeSendMail($data, $status);
    }
}
