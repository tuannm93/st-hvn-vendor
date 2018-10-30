<?php

namespace App\Services\Aws;

use App\Repositories\DeviceInfoRepositoryInterface;
use App\Services\BaseService;
use App\Services\Log\CorpLogService;
use Aws\Sns\SnsClient;
use Monolog\Logger;

class AwsUtilService extends BaseService
{
    const TOPIC_ARN = 'arn:aws:sns:ap-northeast-1:041916388413:app/APNS/OrangeCommisionApp';
    const IOS_PLATFORM_ARN = 'arn:aws:sns:ap-northeast-1:041916388413:app/APNS_SANDBOX/CyzenTestPush';
    const ANDROID_PLATFORM_ARN = 'arn:aws:sns:ap-northeast-1:041916388413:app/GCM/ORANGE-SYSTEM';
    /**
     * @var SnsClient
     */
    private $sns;
    /**
     * @var string
     */
    private $platformArn;
    /**
     * @var DeviceInfoRepositoryInterface
     */
    private $deviceRepo;
    /**
     * @var CorpLogService
     */
    private $logger;

    /**
     * AwsUtilService constructor.
     *
     * @param DeviceInfoRepositoryInterface $deviceRepo
     */
    public function __construct(DeviceInfoRepositoryInterface $deviceRepo)
    {
        $args = [
            'credentials' => [
                'key' => env('AWS_KEY', 'AKIAIWUMZJEGBX7W3KPA'),
                'secret' => env('AWS_SECRET', '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'),
            ],
            'region' => env('AWS_REGION', 'ap-northeast-1'),
            'version' => env('AWS_SNS_VERSION', 'latest'),
        ];

        $this->sns = new SnsClient($args);
        $this->deviceRepo = $deviceRepo;
        $this->logger = new CorpLogService('logs/aws.log', 'PUSH NOTIFY:');
    }

    /**
     * @param string $deviceToken
     * @param string $osType
     * @return \Aws\Result|string
     */
    public function createEndpoint($deviceToken, $osType)
    {
        $result = '';
        //iOS
        $this->platformArn = env('IOS_PLATFORM_ARN', AwsUtilService::IOS_PLATFORM_ARN);

        //Android
        if ($osType == 'Android') {
            $this->platformArn = env('ANDROID_PLATFORM_ARN', AwsUtilService::ANDROID_PLATFORM_ARN);
        }

        try {
            $option = [
                'PlatformApplicationArn' => $this->platformArn,
                'Token' => $deviceToken,
            ];

            $result = $this->sns->createPlatformEndpoint($option);

            if ($result) {
                $subscribeOption = [
                    'TopicArn' => env('TOPIC_ARN', AwsUtilService::TOPIC_ARN),
                    'Protocol' => 'application',
                    'Endpoint' => $result['EndpointArn'],
                ];

                $this->sns->subscribe($subscribeOption);
            }
        } catch (\Exception $e) {
            logger(__METHOD__ . ' create endpoint error : ' . $e->getMessage());
        }

        return $result;
    }

    /**
     * @param string $endpoint
     * @return \Aws\Result|string
     */
    public function getEndpoint($endpoint)
    {
        $result = '';

        try {
            $option = [
                'EndpointArn' => $endpoint,
            ];

            $result = $this->sns->getEndpointAttributes($option);
        } catch (\Exception $e) {
            logger(__METHOD__ . ' get endpoint error : ' . $e->getMessage());
        }

        return $result;
    }

    /**
     * @param string $endpoint
     * @return \Aws\Result|string
     */
    public function setEndpoint($endpoint)
    {
        $result = '';

        try {
            $option = [
                'EndpointArn' => $endpoint,
                'Attributes' => [
                    'Enabled' => 'true',
                ],
            ];

            $result = $this->sns->setEndpointAttributes($option);
        } catch (\Exception $e) {
            logger(__METHOD__ . ' set endpoint error : ' . $e->getMessage());
        }

        return $result;
    }

    /**
     * @param integer $userId
     * @param string $pushMessage
     * @param array $extendData
     * @throws \Exception
     */
    public function publish($userId, $pushMessage, $extendData = [])
    {
        //Retrieve the endpoint associated with user_id from the device_infos table
        $targetArn = $this->deviceRepo->getDeviceInfoByUserId($userId);
        //Send notification
        //When there are multiple end points
        $pushParameter = [];
        $pushCnt = 0;
        foreach ($targetArn as $deviceInfo) {
            if ($deviceInfo->os_type == 'Android') {
                //Androidの場合
                if (empty($extendData)) {
                    $pushParameter = [
                        'Message' => $pushMessage,
                        'TargetArn' => $deviceInfo->endpoint,
                    ];
                } else {
                    $extendData['message'] = $pushMessage;
                    $pushParameter = [
                        'Message' => json_encode($extendData),
                        'TargetArn' => $deviceInfo->endpoint,
                    ];
                }
            } elseif ($deviceInfo->os_type == 'iOS') {
                //For iOS
                //Current number of unread notices
                $pushCnt = $deviceInfo->push_cnt;
                // In the test environment APNS_SANDBOX
                // In the production environment APNS
                $environmentKey = env('AWS_ENVIRONMENT_MESSAGE_IOS', 'APNS');
                $pushParameter = [
                    'MessageStructure' => 'json',
                    'TargetArn' => $deviceInfo->endpoint,
                    'Message' => json_encode([
                        $environmentKey => json_encode([
                            'aps' => [
                                'alert' => $pushMessage,
                                'badge' => $pushCnt + 1,
                                'sound' => 'default',
                                'content-available' => 1,
                                'data' => $extendData
                            ]
                        ]),
                    ]),
                ];
            }
            try {
                $this->sns->publish($pushParameter);
                if ($deviceInfo->os_type == 'Android') {
                    $upData = ['last_push_sender_time' => date('Y-m-d H:i:s')];
                } elseif ($deviceInfo->os_type == 'iOS') {
                    $upData = [
                        'push_cnt' => $pushCnt + 1,
                        'last_push_sender_time' => date('Y-m-d H:i:s'),
                    ];
                }
                $this->logger->log('__SNS_SUCCESS: ', $deviceInfo->toArray(), 200);
                $this->logger->log('__SNS_EXTERNAL_DATA: ', json_encode($extendData), 200);
                //When the push succeeds, update the latest notification transmission date and time of device_infos (iOS also updates the number of icon badges)
                $updated = $this->deviceRepo->updateById($deviceInfo->id, $upData);
                $logData = ['device_info' => $deviceInfo->toArray(), 'update_data' => $upData];
                if ($updated) {
                    $this->logger->log('update device_infos success', $logData, 200);
                } else {
                    $this->logger->log('update device_infos error', $logData, 400);
                }
            } catch (\Exception $ex) {
                $this->logger->log('EX MESSAGE: ', $ex->getMessage(), Logger::CRITICAL);
                $this->logger->log('push failed', $deviceInfo->toArray(), 400);
                //Delete the endpoint if an exception occurs from AmazonSNS
                $this->deleteEndpoint($deviceInfo->endpoint);
                //Also delete from DB
                $updated = $this->deviceRepo->updateById($deviceInfo->id, ['del_flg' => 1]);
                //Leave it in the log
                $logData = ['device_info' => $deviceInfo->toArray(), 'DB result' => $updated];
                if ($updated) {
                    $this->logger->log('update device_infos success', $logData, 300);
                } else {
                    $this->logger->log('update device_infos success', $logData, 400);
                }
            }
        }
    }

    /**
     * @param string $endpoint
     * @throws \Exception
     */
    public function deleteEndpoint($endpoint)
    {
        try {
            $this->sns->deleteEndpoint(['EndpointArn' => $endpoint]);
        } catch (\Exception $ex) {
            $dataLog = ['endpoint' => $endpoint, 'err_msg' => $ex->getMessage()];
            $this->logger->log('delete endpoint error', $dataLog, 400);
        }
    }
}
