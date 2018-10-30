<?php

namespace App\Http\Controllers\Accumulated;

use App\Http\Controllers\BaseController;
use App\Repositories\AccumulatedInformationsRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class AccumulatedInformationController extends BaseController
{
    /**
     * @var AccumulatedInformationsRepositoryInterface
     */
    protected $accumulatedInfoRepo;

    /**
     * AccumulatedInformationController constructor.
     *
     * @param AccumulatedInformationsRepositoryInterface $accumulatedInfoRepo
     */
    public function __construct(
        AccumulatedInformationsRepositoryInterface $accumulatedInfoRepo
    ) {
        parent::__construct();
        $this->accumulatedInfoRepo = $accumulatedInfoRepo;
    }

    /**
     * Show image
     *
     * @param  \Illuminate\Http\Request $request
     * @return mixed
     */
    public function mailOpen(Request $request)
    {
        $publicPath = public_path('/img/mail_img.gif');
        $demandId = $request->input("demandId", null);
        $corpId = $request->input("corpId", null);

        if (empty($demandId) || empty($corpId)) {
            return response()->file($publicPath, ["Content-type: image/gif"]);
        }

        try {
            $results = $this->accumulatedInfoRepo->getInfoByFlag($corpId, $demandId);
            foreach ($results as $result) {
                $this->accumulatedInfoRepo->updateOrCreate(
                    $result->id,
                    [
                    "mail_open_flag" => 1,
                    "mail_open_date" => date('Y-m-d H:i'),
                    "modified_user_id" => $corpId
                    ]
                );
            }
        } catch (\Exception $exception) {
            Log::error("AccumulatedInformation 保存エラー". $exception->getMessage());
        }

        return response()->file($publicPath, ["Content-type: image/gif"]);
    }
}
