<?php

namespace App\Repositories\Eloquent;

use App\Models\Refusals;
use App\Repositories\RefusalsRepositoryInterface;
use Auth;

class RefusalsRepository extends SingleKeyModelRepository implements RefusalsRepositoryInterface
{
    /**
     * @var Refusals
     */
    protected $model;

    /**
     * RefusalsRepository constructor.
     *
     * @param Refusals $model
     */
    public function __construct(Refusals $model)
    {
        $this->model = $model;
    }

    /**
     * @return \App\Models\Base|Refusals|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new Refusals();
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
        ];
    }

    /**
     * @param integer $auctionId
     * @param string $dataUpdate
     * @return bool|mixed
     */
    public function updateData($auctionId, $dataUpdate)
    {
        try {
            $checkRefusal = $this->model->where('auction_id', $auctionId)->first();
            if ($checkRefusal) {
                $refusal = $checkRefusal;
            } else {
                $refusal = $this->getBlankModel();
                $refusal->created_user_id = Auth::getUser()->user_id;
                $refusal->created = date('Y-m-d H:i:s');
                $refusal->auction_id = $auctionId;
            }
            $refusal->estimable_time_from = $dataUpdate['estimable_time_from'];
            $refusal->contactable_time_from = $dataUpdate['contactable_time_from'];
            $refusal->other_contens = $dataUpdate['other_contents'];
            $refusal->modified_user_id = Auth::getUser()->user_id;
            $refusal->modified = date('Y-m-d H:i:s');
            $refusal->save();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
