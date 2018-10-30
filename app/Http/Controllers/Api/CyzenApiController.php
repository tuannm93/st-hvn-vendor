<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Auth\LoginController;
use App\Repositories\DeviceInfoRepositoryInterface;
use App\Services\Cyzen\CyzenNotificationServices;
use App\Services\CyzenApi\CyzenDemandInfoService;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Session;

class CyzenApiController
{
    use AuthenticatesUsers;

    const API_SUCCESS = 200;
    const API_ERROR = 500;
    const API_ERROR_COMMISSION_CHANGED = 501;
    const API_ERROR_USER_NOT_IN_DEMAND = 405;
    /**
     * @var CyzenDemandInfoService $cyzenDemandInfoService
     */
    protected $cyzenDemandInfoService;

    /**
     * @var \Monolog\Logger $logger
     */
    protected $logger;

    /**
     * @var string $logPath
     */
    protected $logPath = 'logs/cyzen/cyzen_api';

    /**
     * CyzenApiController constructor.
     *
     * @param \App\Services\CyzenApi\CyzenDemandInfoService $cyzenDemandInfoService
     * @throws \Exception
     */
    /**
     * @var DeviceInfoRepositoryInterface
     */
    public $deviceRepo;

    /**
     * CyzenApiController constructor.
     * @param CyzenDemandInfoService $cyzenDemandInfoService
     * @param DeviceInfoRepositoryInterface $deviceRepo
     * @throws \Exception
     */
    public function __construct(CyzenDemandInfoService $cyzenDemandInfoService, DeviceInfoRepositoryInterface $deviceRepo)
    {
        $this->cyzenDemandInfoService = $cyzenDemandInfoService;
        $this->logger = new Logger('cyzen_api');
        $this->deviceRepo = $deviceRepo;
        $this->logger->pushHandler(new StreamHandler(createLogPathCyzen($this->logPath), Logger::INFO));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDemandDetail($id)
    {
        try {
            $user = \JWTAuth::parseToken()->authenticate();
            $demand = $this->cyzenDemandInfoService->getDemandInfo($id, $user->user_id);

            if (!empty($demand)) {
                $demand[0]->user = $user;
                $result = $this->cyzenDemandInfoService->initApiReturn($demand[0]);
                $msg = 'success';
            } else {
                $result = new \stdClass();
                $msg = __('commission.not_have_demand_for_user');
                return $this->apiResponse(self::API_ERROR_USER_NOT_IN_DEMAND, $msg, $result);
            }
            return $this->apiResponse(self::API_SUCCESS, $msg, $result);
        } catch (\Exception $ex) {
            $this->logger->log(Logger::ERROR, $ex->getMessage());
            return $this->apiResponse(self::API_ERROR, 'error');
        }
    }

    /**
     * @param $code
     * @param string $message
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiResponse($code, $message = '', $data = [])
    {
        return response()->json([
            'code' => $code,
            'msg' => $message,
            'data' => $data
        ]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function updateDemandStatus(Request $request)
    {
        try {
            $user = \JWTAuth::parseToken()->authenticate();
            $state = $request->json('state');
            $demandId = $request->json('demand_id');
            $staffId = $user->user_id;
            $updateDemand = $this->cyzenDemandInfoService->updateDemandStatus($demandId, $staffId, $state);

            if ($updateDemand == self::API_SUCCESS) {
                if ($state == CyzenNotificationServices::STATUS_STARTED_WORK ||
                    $state == CyzenNotificationServices::STATUS_END_WORK) {
                    $data = [
                        'demand_id' => $demandId,
                        'user_id' => $staffId
                    ];
                    $this->cyzenDemandInfoService->sendMail($data, $state);
                }
                $this->logger->log(Logger::INFO, $demandId . ' --- ' . $staffId . ' --- ' . $state);
                return $this->apiResponse($updateDemand, 'success', []);
            } else {
                $message = trans('commission.message_update_status_fail');

                if ($updateDemand == self::API_ERROR_COMMISSION_CHANGED) {
                    $message = trans('commission.message_status_changed');
                }

                return $this->apiResponse($updateDemand, $message);
            }
        } catch (\Exception $ex) {
            $this->logger->log(Logger::ERROR, $ex->getMessage());
            return $this->apiResponse(self::API_ERROR, 'error');
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function logoutPIC()
    {
        try {
            $agent = new Agent();
            if ($agent->isAndroidOS()) {
                $this->deviceRepo->deleteDeviceByUserId(auth()->user()->user_id, 'Android');
            }
            if ($agent->isiOS()) {
                $this->deviceRepo->deleteDeviceByUserId(auth()->user()->user_id, 'iOS');
            }
            $this->guard()->logout();
            Session::put(LoginController::LOGOUT_FOR_MOBILE, 'log_out');
            return $this->apiResponse(self::API_SUCCESS, 'success', []);
        } catch (\Exception $ex) {
            $this->logger->log(Logger::ERROR, $ex->getMessage());
            return $this->apiResponse(self::API_ERROR, 'error');
        }
    }
}
