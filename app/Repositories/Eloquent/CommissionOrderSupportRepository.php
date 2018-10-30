<?php

namespace App\Repositories\Eloquent;

use App\Repositories\CommissionOrderSupportRepositoryInterface;
use App\Models\CommissionOrderSupport;
use Illuminate\Support\Facades\Auth;

class CommissionOrderSupportRepository extends SingleKeyModelRepository implements CommissionOrderSupportRepositoryInterface
{
    /**
     * @var CommissionOrderSupport
     */
    private $model;

    /**
     * CommissionInfoRepository constructor.
     *
     * @param CommissionOrderSupport $model
     */
    public function __construct(CommissionOrderSupport $model)
    {
        $this->model = $model;
    }

    /**
     * @return \App\Models\Base|CommissionOrderSupport|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new CommissionOrderSupport();
    }

    /**
     * @param array $data
     * @return \App\Models\Base|CommissionOrderSupport|bool|\Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function save($data)
    {
        $commissonOrderSupport = $this->model;

        if (isset($data['id'])) {
            $commissonOrderSupport = $this->model->where('id', $data['id'])->first();
        }

        $commissonOrderSupport->commission_id = $data['commission_id'];
        $commissonOrderSupport->correspond_status = $data['correspond_status'];
        $commissonOrderSupport->correspond_datetime = $data['correspond_datetime'];
        $commissonOrderSupport->order_fail_reason = $data['order_fail_reason'];
        $commissonOrderSupport->responders = $data['responders'];
        $commissonOrderSupport->corresponding_contens = $data['corresponding_contens'];
        $commissonOrderSupport->modified_user_id = Auth::user()->user_id;
        $commissonOrderSupport->created_user_id = Auth::user()->user_id;
        $commissonOrderSupport->modified = date('Y-m-d H:i:s');
        $commissonOrderSupport->created = date('Y-m-d H:i:s');

        $commissonOrderSupport->save();

        return $commissonOrderSupport;
    }

    /**
     * @param integer $commissionId
     * @param boolean $all
     * @return array
     */
    public function findByCommissionId($commissionId, $all = false)
    {
        $result = $this->model
                    ->from('commission_order_supports')
                    ->where('commission_id', $commissionId)
                    ->orderBy('modified', 'DESC');

        if ($all == true) {
            $result = $result->get()->toArray();
        } else {
            $result = $result->first();
        }

        return $result;
    }
}
