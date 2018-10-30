<?php

namespace App\Repositories\Eloquent;

use App\Repositories\CommissionCorrespondsRepositoryInterface;
use App\Models\CommissionCorrespond;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\UserService;

class CommissionCorrespondsRepository extends SingleKeyModelRepository implements CommissionCorrespondsRepositoryInterface
{
    /**
     * @var CommissionCorrespond
     */
    protected $model;

    /**
     * CommissionCorrespondsRepository constructor.
     *
     * @param CommissionCorrespond $model
     */
    public function __construct(CommissionCorrespond $model)
    {
        $this->model = $model;
    }

    /**
     * @return \App\Models\Base|CommissionCorrespond|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new CommissionCorrespond();
    }

    /**
     * Description function: save change value into table commission_corresponds
     *
     * @param  \App\Models\Base $data
     * @return \App\Models\Base|bool|mixed
     */
    public function save($data)
    {
        $commissionCorrespond = $this->getBlankModel();

        if (isset($data['id'])) {
            $commissionCorrespond = $this->model->where('id', $data['id'])->first();
        }

        $commissionCorrespond->modified = Carbon::now();
        if (empty($commissionCorrespond->id)) {
            $commissionCorrespond->created = Carbon::now();
        }
        $commissionCorrespond->corresponding_contens = $data['corresponding_contens'];
        $commissionCorrespond->correspond_datetime = $data['correspond_datetime'];

        if (isset($data['responders'])) {
            $commissionCorrespond->responders = $data['responders'];
        }

        $commissionCorrespond->corresponding_contens = $data['corresponding_contens'];
        $commissionCorrespond->correspond_datetime = $data['correspond_datetime'];

        if (isset($data['rits_responders'])) {
            $commissionCorrespond->rits_responders = $data['rits_responders'];
        }

        if (isset($data['commission_id'])) {
            $commissionCorrespond->commission_id = $data['commission_id'];
        }

        if (isset($data['created_user_id'])) {
            $commissionCorrespond->created_user_id = $data['created_user_id'];
        } elseif (empty($commissionCorrespond->id)) {
            $commissionCorrespond->created_user_id = Auth::user()->user_id;
        }

        if (isset($data['modified_user_id'])) {
            $commissionCorrespond->modified_user_id = $data['modified_user_id'];
        } else {
            $commissionCorrespond->modified_user_id = Auth::user()->user_id;
        }

        $result = $commissionCorrespond->save();

        return $result;
    }

    /**
     * @param integer $id
     * @return array
     */
    public function findByIdWithUserName($id)
    {
        $fields = $this->getAllTableFieldsByAlias('commission_corresponds', 'CommissionCorrespond');

        $query = $this->model->from('commission_corresponds AS CommissionCorrespond')
            ->leftjoin(
                'm_users AS MUser',
                function ($join) {
                            $join->on(DB::raw('cast("CommissionCorrespond"."rits_responders" AS integer)'), '=', 'MUser.id');
                }
            )
                    ->where('CommissionCorrespond.commission_id', '=', $id);

        if (UserService::checkRole('affiliation')) {
            $query->where('CommissionCorrespond.modified_user_id', '=', Auth::user()->user_id);
        }

        $query->orderBy('CommissionCorrespond.id', 'DESC')
            ->select($fields)
            ->addSelect('MUser.user_name AS MUser__user_name');

        $result = $query->get()->toArray();

        return $result;
    }
}
