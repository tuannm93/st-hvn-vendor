<?php

namespace App\Services\Cyzen;

use App\Repositories\CyzenModelRepositoryInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Database\QueryException;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class BaseCyzenServices
{
    /**
     * Log path
     */
    const LOG_PATH_GROUP = 'logs/cyzen/groups';
    const LOG_PATH_USER = 'logs/cyzen/users';
    const LOG_PATH_USER_GROUP = 'logs/cyzen/userGroups';
    const LOG_PATH_SPOT = 'logs/cyzen/spots';
    const LOG_PATH_SCHEDULE = 'logs/cyzen/schedules';
    const LOG_PATH_SCHEDULE_ONE_DAY = 'logs/cyzen/schedules_one_day';
    const LOG_PATH_TRACKING = 'logs/cyzen/trackings';
    const LOG_PATH_HISTORY = 'logs/cyzen/histories';
    const LOG_PATH_SPOT_TAG = 'logs/cyzen/spot_tags';
    const LOG_PATH_STATUS = 'logs/cyzen/status';
    const TIME_FORMAT_CYZEN_API = 'Y-m-d\TH:i:s';
    /**
     * @var \Monolog\Logger $logger
     */
    public $logger;
    /**
     * @var \GuzzleHttp\Client $client
     */
    protected $client;
    /**
     * @var string $token
     */
    protected $token;

    /**
     * @var string $contentType
     */
    protected $contentType;

    /**
     * @var string $companyId
     */
    protected $companyId;

    /**
     * @var string $baseUri
     */
    protected $baseUri;

    /**
     * @var int $limitReconnect
     */
    private $limitReconnect = 5;

    /**
     * BaseCyzenServices constructor.
     *
     * @param $logPath
     * @throws \Exception
     */
    public function __construct($logPath = null)
    {
        date_default_timezone_set(config('app.timezone'));
        $this->client = new Client();
        $this->token = env(
            'CYZEN_API_TOKEN',
            'Bearer vdethxWv2ifAHMTABufQoL66bY7fAJAUo0sEqGFOQP2JtbCemrw5zM87htKacPT4'
        );
        $this->baseUri = env('CYZEN_API_BASE_URL', 'https://ext.cyzen.cloud');
        $this->companyId = env('CYZEN_API_COMPANY_ID', 'f3fabcfe5598b54a385ab06cd06197c7');
        $this->contentType = env('CYZEN_API_CONTENT_TYPE', 'application/x-www-form-urlencoded');
        $this->limitReconnect = (int)env('CYZEN_API_TIME_RECONNECT_LIMIT', 5);
        if (!empty($logPath)) {
            $this->logger = new Logger('Cyzen');
            $this->logger->pushHandler(new StreamHandler(createLogPathCyzen($logPath), Logger::INFO));
        }
    }

    /**
     * @param $id
     * @param $relationService
     * @param $currentRepo
     * @return bool
     */
    public function checkDbRelationship($id, $relationService, $currentRepo)
    {
        try {
            if (!$currentRepo->checkForeignKey($id, $relationService->getModel())) {
                //call $model service to get data
                return $relationService->getById($id);
            }
            return true;
        } catch (QueryException $ex) {
            $this->logger->log(Logger::CRITICAL, $ex->getMessage());
        }
        return false;
    }

    /**
     * @param $data
     * @param CyzenModelRepositoryInterface $repository
     * @return mixed
     */
    public function processingData($data, $repository)
    {
        try {
            return $repository->saveData($data);
        } catch (QueryException $ex) {
            $this->logger->log(Logger::CRITICAL, $ex->getMessage());
            return false;
        }
    }

    /**
     * @param $path
     * @param array $data
     * @param int $reconnectTime
     * @return array|mixed|null
     *@throws \Exception
     */
    public function put($path, array $data, $reconnectTime = 0)
    {
        try {
            if ($this->limitReconnect == $reconnectTime) {
                $this->logger->log(Logger::ERROR, 'ERROR FOR LIMIT CONNECT');
                return [];
            };
            $reconnectTime++;
            $data['company_id'] = $this->companyId;
            $response = $this->client->request(
                'PUT',
                $this->baseUri . $path,
                [
                    'form_params' => $data,
                    'headers' => ['Authorization' => $this->token],
                    'verify' => false
                ]
            );
            $content = $response->getBody()->getContents();
            $this->logger->log(Logger::INFO, 'PUT-' . $path . json_encode($data));
            return json_decode($content, true);
        } catch (ClientException $ex) {
            //try get again
            if ((int)$ex->getCode() == 403) {
                return $this->post($path, $data, $reconnectTime);
            }
            $this->logger->log(Logger::ERROR, $ex->getMessage() . json_encode($data));
        }
        return null;
    }

    /**
     * @param $path
     * @param array $data
     * @param int $reconnectTime
     * @return mixed
     * @throws \Exception
     */
    public function post($path, array $data, $reconnectTime = 0)
    {
        try {
            if ($this->limitReconnect == $reconnectTime) {
                $this->logger->log(Logger::ERROR, 'ERROR FOR LIMIT CONNECT');
                return [];
            };
            $reconnectTime++;

            $data['company_id'] = $this->companyId;
            $response = $this->client->request(
                'POST',
                $this->baseUri . $path,
                [
                    'form_params' => $data,
                    'headers' => ['Authorization' => $this->token],
                    'verify' => false
                ]
            );
            $content = $response->getBody()->getContents();
            $this->logger->log(Logger::INFO, 'POST-' . $path . ': ' . json_encode($data) . ' - ' . $content);
            return json_decode($content, true);
        } catch (ClientException $ex) {
            //try get again
            if ((int)$ex->getCode() == 403) {
                return $this->post($path, $data, $reconnectTime);
            }
            $this->logger->log(Logger::ERROR, $ex->getMessage() . json_encode($data));
        }
        return null;
    }

    /**
     * @param $path
     * @param array $query
     * @param int $reconnectTime
     * @return mixed
     * @throws \Exception
     */
    protected function get($path, array $query, $reconnectTime = 0)
    {
        try {
            if ($this->limitReconnect == $reconnectTime) {
                $this->logger->log(Logger::ERROR, 'ERROR FOR LIMIT CONNECT');
                return [];
            };
            $reconnectTime++;

            $query['company_id'] = $this->companyId;
            $response = $this->client->request(
                'GET',
                $this->baseUri . $path,
                [
                    'query' => $query,
                    'headers' => ['Authorization' => $this->token],
                    'verify' => false
                ]
            );
            $content = $response->getBody()->getContents();
            $this->logger->log(Logger::INFO, 'GET-' . $path . json_encode($query));
            return json_decode($content, true);
        } catch (ClientException $ex) {
            //try get again
            if ((int)$ex->getCode() == 403) {
                return $this->get($path, $query, $reconnectTime);
            }
            $this->logger->log(Logger::ERROR, $ex->getMessage() . json_encode($query));
            return null;
        }
    }
}
