<?php

namespace App\Repositories\Eloquent;

use App\Repositories\AdditionInfoRepositoryInterface;
use App\Models\AdditionInfo;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Log;

class AdditionInfoRepository extends SingleKeyModelRepository implements AdditionInfoRepositoryInterface
{
    /**
     * @var AdditionInfo
     */
    protected $model;

    /**
     * AdditionInfoRepository constructor.
     *
     * @param AdditionInfo $model
     */
    public function __construct(AdditionInfo $model)
    {
        $this->model = $model;
    }

    /**
     * @return AdditionInfo|\App\Models\Base|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new AdditionInfo();
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
     * @param \App\Models\Base $data
     * @return \App\Models\Base|bool
     * @throws \Exception
     */
    public function save($data)
    {
        try {
            DB::beginTransaction();
            $data['created'] = Carbon::now();
            $data['created_user_id'] = auth()->user()->user_id;
            $data['modified'] = Carbon::now();
            $data['modified_user_id'] = auth()->user()->user_id;
            $this->model->insert($data);
            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            return false;
        }
    }

    /**
     * @param array $conditions
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getAdditionList($conditions)
    {
        $isMobile = false;
        if (isset($conditions['isMobile'])) {
            $isMobile = $conditions['isMobile'];
            unset($conditions['isMobile']);
        }
        if ($isMobile) {
            return $this->model->where($conditions)->with('genres')->orderBy('id', 'desc')->paginate(2);
        }
        return $this->model->where($conditions)->with('genres')->orderBy('id', 'desc')->get();
    }

    /**
     * @param integer $id
     * @return bool|null
     * @throws \Exception
     */
    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $dataDelete = [
                'del_flg' => 1,
                'modified' => date('Y-m-d H:i:s'),
                'modified_user_id' => auth()->user()->user_id
            ];
            $this->model->find($id)->update($dataDelete);
            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            return false;
        }
    }

    /**
     * @param array $fields
     * @param array $orderBy
     * @param array $conditions
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getReportAdditionList($fields, $orderBy, $conditions)
    {
        $query = $this->model
            ->select($fields)
            ->join('m_genres', 'm_genres.id', '=', 'addition_infos.genre_id')
            ->join('m_corps', 'm_corps.id', '=', 'addition_infos.corp_id')
            ->where($conditions);
        foreach ($orderBy as $key => $value) {
            $query = $query->orderBy($key, $value);
        }
        return $query->paginate(config('rits.list_limit'));
    }

    /**
     * @param string $fields
     * @param array $conditions
     * @param string $orderBy
     * @return \Illuminate\Support\Collection
     */
    public function getDataCSV($fields, $conditions, $orderBy)
    {
        $virtualFields = [
            DB::raw("(CASE WHEN addition_infos.falsity_flg = 1 THEN '有' ELSE '無' END) as addition_infos_falsity"),
            DB::raw("(CASE WHEN addition_infos.demand_flg = 1 THEN 'チェック有' ELSE 'チェック無' END) as addition_infos_demand"),
            DB::raw("(CASE WHEN addition_infos.demand_type_update = 1 THEN '復活案件' WHEN addition_infos.demand_type_update = 2 THEN '追加施工' WHEN addition_infos.demand_type_update = 3 THEN 'その他' ELSE '' END) as addition_infos_demand_type_update_text")
        ];
        foreach ($virtualFields as $field) {
            $fields[] = $field;
        }
        $query = $this->model::select($fields)
            ->join('m_genres', 'm_genres.id', '=', 'addition_infos.genre_id')
            ->where($conditions);
        foreach ($orderBy as $key => $value) {
            $query = $query->orderBy($key, $value);
        }
        return $query->get();
    }

    /**
     * @param object $model
     * @param array  $input
     * @return \App\Models\Base|bool
     */
    public function update($model, $input)
    {
        foreach ($model->getEditableColumns() as $column) {
            if (array_key_exists($column, $input)) {
                $model->$column = array_get($input, $column);
            }
        }

        if ($this->cacheEnabled) {
            $primaryKey = $this->getPrimaryKey();
            $key = $this->getCacheKey([$model->$primaryKey]);
            \Log::info("Cache Remove $key");
            \Cache::forget($key);
        }

        return $model->save();
    }
}
