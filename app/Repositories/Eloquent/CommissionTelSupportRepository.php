<?php

namespace App\Repositories\Eloquent;

use App\Repositories\CommissionTelSupportRepositoryInterface;
use App\Models\CommissionTelSupport;
use Illuminate\Support\Facades\Auth;

class CommissionTelSupportRepository extends SingleKeyModelRepository implements CommissionTelSupportRepositoryInterface
{
    /**
     * @var CommissionTelSupport
     */
    private $model;

    /**
     * CommissionInfoRepository constructor.
     *
     * @param CommissionTelSupport $model
     */
    public function __construct(CommissionTelSupport $model)
    {
        $this->model = $model;
    }
    /**
     * @return \App\Models\Base|CommissionTelSupport|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new CommissionTelSupport();
    }
    /**
     * @param array $data
     * @return \App\Models\Base|CommissionTelSupport|bool|\Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function save($data)
    {
        $commissonTelSupport = $this->model;

        if (isset($data['id'])) {
            $commissonTelSupport = $this->model->where('id', $data['id'])->first();
        }

        $commissonTelSupport->commission_id = $data['commission_id'];
        $commissonTelSupport->correspond_status = $data['correspond_status'];
        $commissonTelSupport->correspond_datetime = $data['correspond_datetime'];
        $commissonTelSupport->order_fail_reason = $data['order_fail_reason'];
        $commissonTelSupport->responders = $data['responders'];
        $commissonTelSupport->corresponding_contens = $data['corresponding_contens'];
        $commissonTelSupport->modified_user_id = Auth::user()->user_id;
        $commissonTelSupport->created_user_id = Auth::user()->user_id;
        $commissonTelSupport->modified = date('Y-m-d H:i:s');
        $commissonTelSupport->created = date('Y-m-d H:i:s');

        $commissonTelSupport->save();

        return $commissonTelSupport;
    }

    /**
     * @param integer $commissionId
     * @param boolean $all
     * @return array
     */
    public function findByCommissionId($commissionId, $all = false)
    {
        $result = $this->model
                    ->from('commission_tel_supports')
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
