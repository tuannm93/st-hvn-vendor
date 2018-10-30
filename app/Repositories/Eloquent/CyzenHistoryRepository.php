<?php
namespace App\Repositories\Eloquent;

use App\Models\Cyzen\CyzenHistory;
use App\Repositories\CyzenHistoryRepositoryInterface;
use Illuminate\Database\Query\JoinClause;

class CyzenHistoryRepository extends SingleKeyModelRepository implements CyzenHistoryRepositoryInterface
{
    /**
     * @var \App\Models\Cyzen\CyzenHistory $model
     */
    public $model;

    /**
     * CyzenHistoryRepository constructor.
     *
     * @param \App\Models\Cyzen\CyzenHistory $model
     */
    public function __construct(CyzenHistory $model)
    {
        $this->model = $model;
    }

    /**
     * @return \App\Models\Cyzen\CyzenHistory|mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param $data
     * @return bool
     */
    public function saveData($data)
    {
        $history = $this->model->find($data['id']);

        if (empty($history)) {
            return $this->model->insert($data);
        }

        foreach ($data as $key => $value) {
            $history->$key = $value;
        }
        return $history->save();
    }

    /**
     * @param $field
     * @return mixed
     */
    public function max($field)
    {
        return $this->model->where([])->orderBy($field, 'DESC')->first();
    }

    /**
     * @param $key
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return bool
     */
    public function checkForeignKey($key, $model)
    {
        $hasDataRelation = $model->find($key);
        return ($hasDataRelation) ? true : false;
    }

    /**
     * @param $listStaff
     * @return mixed
     */
    public function getStatusOfStaffs($listStaff)
    {
        $query = $this->model->select(
            ['m_staffs.sp_user_id',
            'cyzen_histories.status_id',
            'cyzen_statuses.status_name',
            'cyzen_histories.updated_at']
        )
            ->join('cyzen_statuses', 'cyzen_histories.status_id', '=', 'cyzen_statuses.status_id')
            ->join('m_staffs', 'cyzen_histories.user_id', '=', 'm_staffs.cyzen_user_id')
            ->join(\DB::raw('(SELECT cyzen_histories.user_id, Max(cyzen_histories.updated_at)
                FROM cyzen_histories GROUP BY cyzen_histories.user_id) AS maxhistory'), function ($join) {
                /** @var JoinClause $join */
                $join->on('maxhistory.user_id', '=', 'cyzen_histories.user_id')
                    ->on('maxhistory.max', '=', 'cyzen_histories.updated_at');
            })
            ->whereIn('m_staffs.sp_user_id', $listStaff)->distinct();
        return $query->get()->toArray();
    }
}
