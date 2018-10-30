<?php

namespace App\Repositories\Eloquent;

use App\Repositories\CommissionVisitSupportRepositoryInterface;
use App\Models\CommissionVisitSupport;
use Illuminate\Support\Facades\Auth;

class CommissionVisitSupportRepository extends SingleKeyModelRepository implements CommissionVisitSupportRepositoryInterface
{
    /**
     * @var CommissionVisitSupport
     */
    private $model;

    /**
     * CommissionInfoRepository constructor.
     *
     * @param CommissionVisitSupport $model
     */
    public function __construct(CommissionVisitSupport $model)
    {
        $this->model = $model;
    }

    /**
     * @return \App\Models\Base|CommissionVisitSupport|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new CommissionVisitSupport();
    }

    /**
     * @param array $data
     * @return \App\Models\Base|CommissionVisitSupport|bool|\Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function save($data)
    {
        $commissonVisitSupport = $this->model;

        if (isset($data['id'])) {
            $commissonVisitSupport = $this->model->where('id', $data['id'])->first();
        }

        $commissonVisitSupport->commission_id = $data['commission_id'];
        $commissonVisitSupport->correspond_status = $data['correspond_status'];
        $commissonVisitSupport->correspond_datetime = $data['correspond_datetime'];
        $commissonVisitSupport->order_fail_reason = $data['order_fail_reason'];
        $commissonVisitSupport->responders = $data['responders'];
        $commissonVisitSupport->corresponding_contens = $data['corresponding_contens'];
        $commissonVisitSupport->modified_user_id = Auth::user()->user_id;
        $commissonVisitSupport->created_user_id = Auth::user()->user_id;
        $commissonVisitSupport->modified = date('Y-m-d H:i:s');
        $commissonVisitSupport->created = date('Y-m-d H:i:s');

        $commissonVisitSupport->save();

        return $commissonVisitSupport;
    }

    /**
     * @param integer $commissionId
     * @param boolean $all
     * @return array
     */
    public function findByCommissionId($commissionId, $all = false)
    {
        $result = $this->model
                    ->from('commission_visit_supports')
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
