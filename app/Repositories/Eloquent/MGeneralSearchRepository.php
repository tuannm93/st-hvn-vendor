<?php

namespace App\Repositories\Eloquent;

use App\Models\MGeneralSearch;
use App\Repositories\MGeneralSearchRepositoryInterface;
use DB;
use Illuminate\Support\Facades\Auth;

class MGeneralSearchRepository extends SingleKeyModelRepository implements MGeneralSearchRepositoryInterface
{
    /**
     * @var MGeneralSearch
     */
    protected $model;

    /**
     * MGeneralSearchRepository constructor.
     *
     * @param MGeneralSearch $model
     */
    public function __construct(MGeneralSearch $model)
    {
        $this->model = $model;
    }

    /**
     * @param integer $mGeneralId
     * @return array|mixed
     */
    public function findGeneralSearch($mGeneralId)
    {
        return $this->model->where('id', $mGeneralId)->with(['gsCondition', 'gsItem'])->get()->toarray();
    }

    /**
     * @param array $whereConditions
     * @param array $orwhereConditions
     * @return array|mixed
     */
    public function findGeneralSearchAuth($whereConditions, $orwhereConditions)
    {
        return $this->model->select('m_general_searches.*', 'm_users.user_name')
            ->where($whereConditions)->orWhere($orwhereConditions)
            ->leftJoin('m_users', 'm_users.user_id', '=', 'm_general_searches.created_user_id')
            ->orderBy('id', 'desc')
            ->get()->toarray();
    }

    /**
     * @return mixed
     */
    public function getLastInsertID()
    {
        $result = $this->model->orderBy('id', 'desc')->limit(1)->get()->toarray();
        return $result[0]['id'];
    }

    /**
     * @param string $generalId
     * @return mixed|void
     * @throws \Exception
     */
    public function deleteGeneralSearch($generalId)
    {
        $this->model->where('id', '=', $generalId)->delete();
    }

    /**
     * @param array $data
     * @return mixed|void
     */
    public function insertGeneralSearch($data)
    {
        $data['created'] = date('Y-m-d H:i:s');
        $data['created_user_id'] = Auth::getUser()->user_id;
        $data['modified'] = date('Y-m-d H:i:s');
        $data['modified_user_id'] = Auth::getUser()->user_id;
        $this->model->insert($data);
    }

    /**
     * @param array $data
     * @return mixed|void
     */
    public function updateGeneralSearch($data)
    {
        $data['modified'] = date('Y-m-d H:i:s');
        $data['modified_user_id'] = Auth::getUser()->user_id;
        $this->model->where('id', $data['id'])->update($data);
    }

    /**
     * @param string $sql
     * @return array|mixed
     */
    public function runQueryText($sql)
    {
        return DB::select($sql);
    }
}
