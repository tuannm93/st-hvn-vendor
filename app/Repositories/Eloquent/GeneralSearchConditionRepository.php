<?php

namespace App\Repositories\Eloquent;

use App\Models\GeneralSearchCondition;
use App\Repositories\GeneralSearchConditionRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class GeneralSearchConditionRepository extends SingleKeyModelRepository implements GeneralSearchConditionRepositoryInterface
{
    /**
     * @var GeneralSearchCondition
     */
    protected $model;

    /**
     * GeneralSearchConditionRepository constructor.
     *
     * @param GeneralSearchCondition $model
     */
    public function __construct(GeneralSearchCondition $model)
    {
        $this->model = $model;
    }

    /**
     * @param int $mGeneralId
     * @return bool|void
     * @throws \Exception
     */
    public function deleteById($mGeneralId)
    {
        $this->model->where('general_search_id', '=', $mGeneralId)->delete();
    }

    /**
     * @param integer $mGeneralId
     * @return array|mixed
     */
    public function findGeneralSearchCondition($mGeneralId)
    {
        return $this->model->where('general_search_id', '=', $mGeneralId)->get()->toarray();
    }

    /**
     * @param array $datas
     * @return mixed|void
     */
    public function insertGeneralSearch($datas)
    {
        foreach ($datas as $data) {
            $data['created'] = date('Y-m-d H:i:s');
            $data['created_user_id'] = Auth::getUser()->user_id;
            $data['modified'] = date('Y-m-d H:i:s');
            $data['modified_user_id'] = Auth::getUser()->user_id;
            $this->model->insert($data);
        }
    }
}
