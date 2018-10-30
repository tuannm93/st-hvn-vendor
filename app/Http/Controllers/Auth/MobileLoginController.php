<?php

namespace App\Http\Controllers\Auth;

use App\Repositories\DeviceInfoRepositoryInterface;
use App\Services\Aws\AwsUtilService;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class MobileLoginController extends Controller
{
    use AuthenticatesUsers;
    /**
     * @var DeviceInfoRepositoryInterface
     */
    protected $deviceInfoRepository;
    /**
     * @var AwsUtilService
     */
    private $awsUtilService;

    /**
     * @var string
     */
    private $iosType = 'iOS';
    /**
     * @var string
     */
    private $androidType = 'Android';

    /**
     * MobileLoginController constructor.
     *
     * @param DeviceInfoRepositoryInterface $deviceInfoRepository
     * @param AwsUtilService $awsUtilService
     */
    public function __construct(
        DeviceInfoRepositoryInterface $deviceInfoRepository,
        AwsUtilService $awsUtilService
    ) {
        $this->deviceInfoRepository = $deviceInfoRepository;
        $this->awsUtilService = $awsUtilService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function iosLogin(Request $request)
    {
        $params = $request->all();
        $session = [
            'session_key' => '',
            'session_id' => '',
        ];
        $token = [];
        if (!isset($params['user_id']) || empty($params['user_id'])
            || !isset($params['password']) || empty($params['password'])
        ) {
            logger(__METHOD__ . ' post error : ' . json_encode($params));
        } else {
            logger(__METHOD__ . ' post from Mobile : ' . json_encode($params));
            $request->session()->invalidate();
            $this->guard()->logout();
            if (Auth::check() || $this->attemptLogin($request)) {
                $session = [
                    'session_key' => session_name(),
                    'session_id' => session()->getId(),
                ];

                $this->deviceToken($request, $this->iosType);
                $token = $this->createJWT($request);
                Session::remove(LoginController::LOGOUT_FOR_MOBILE);
            }
        }

        $result = ['posts' => $session, '_serialize' => ['posts'], 'token' => $token];

        return response()->json($result);
    }

    /**
     * @param Request $request
     * @param $osType
     * @return array
     */
    private function deviceToken(Request $request, $osType)
    {
        $params = $request->all();
        $session = [
            'session_key' => '',
            'session_id' => '',
        ];
        if (!Auth::check()) {
            logger(__METHOD__ . ' not login : ' . json_encode($params));
        } else {
            $session = [
                'session_key' => session_name(),
                'session_id' => session()->getId(),
            ];

            $deviceToken = isset($params['device_token']) ? $params['device_token'] : '';
            $deviceToken = preg_replace("/( |　)/", '', $deviceToken);
            $userId = Auth::user()->user_id;

            if (!empty($deviceToken)) {
                $deviceInfo = $this->deviceInfoRepository->findByDeviceToken($deviceToken);

                if ($deviceInfo) {
                    $resGetEnd = $this->awsUtilService->getEndpoint($deviceInfo['DeviceInfo__endpoint']);

                    if (!isset($resGetEnd['Attributes']['Enabled']) || $resGetEnd['Attributes']['Enabled'] == 'false') {
                        $this->awsUtilService->setEndpoint($deviceInfo['DeviceInfo__endpoint']);
                    }

                    $saveData = [
                        'id' => $deviceInfo['DeviceInfo__id'],
                        'push_cnt' => 0
                    ];

                    if ($deviceInfo['DeviceInfo__user_id'] != $userId) {
                        $saveData['user_id'] = $userId;
                    }
                    $this->saveDevice($saveData, $params, 'update');
                } else {
                    $res = $this->awsUtilService->createEndpoint($deviceToken, $osType);

                    $saveData = [
                        'user_id' => $userId,
                        'device_token' => $deviceToken,
                        'endpoint' => isset($res['EndpointArn']) ? $res['EndpointArn'] : '',
                        'os_type' => $osType,
                        'push_cnt' => 0,
                    ];
                    $this->saveDevice($saveData, $params, 'insert');
                }
            }
        }

        $result = ['posts' => $session, '_serialize' => ['posts']];

        return $result;
    }

    /**
     * gen token for device
     * @param Request $request
     * @return false|\Illuminate\Http\JsonResponse|string
     */
    private function createJWT(Request $request)
    {
        $credentials = $request->only('user_id', 'password');
        $token = null;
        try {
            $token = JWTAuth::attempt($credentials);
            return $token;
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'Can not create token'], 500);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function androidLogin(Request $request)
    {
        $params = $request->all();
        $session = [
            'session_key' => '',
            'session_id' => '',
        ];
        $token = [];
        if (!isset($params['user_id']) || empty($params['user_id']) ||
            !isset($params['password']) || empty($params['password'])) {
            logger(__METHOD__ . ' post error : ' . json_encode($params));
        } else {
            logger(__METHOD__ . ' post from Mobile : ' . json_encode($params));
            $request->session()->invalidate();
            $this->guard()->logout();
            if (Auth::check() || $this->attemptLogin($request)) {
                $session = [
                    'session_key' => session_name(),
                    'session_id' => session()->getId(),
                ];

                $this->deviceToken($request, $this->androidType);
                $token = $this->createJWT($request);
                Session::remove(LoginController::LOGOUT_FOR_MOBILE);
            }
        }
        $result = ['posts' => $session, '_serialize' => ['posts'], 'token' => $token];
        return response()->json($result);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'user_id';
    }

    /**
     * save device
     * @param  array $saveData
     * @param  array $params
     * @param  string $type
     */
    public function saveDevice($saveData, $params, $type)
    {
        if ($this->deviceInfoRepository->save($saveData)) {
            logger(__METHOD__ . ' '  . $type . ' device token success : ' . json_encode($params));
        } else {
            logger(__METHOD__ . ' '  . $type . ' device token error : ' . json_encode($params));
        }
    }
}
