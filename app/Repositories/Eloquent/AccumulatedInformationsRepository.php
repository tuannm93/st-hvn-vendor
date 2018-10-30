<?php

namespace App\Repositories\Eloquent;

use App\Models\AccumulatedInformations;
use App\Repositories\AccumulatedInformationsRepositoryInterface;

class AccumulatedInformationsRepository extends SingleKeyModelRepository implements AccumulatedInformationsRepositoryInterface
{
    /**
     * @var AccumulatedInformations
     */
    protected $model;

    /**
     * AccumulatedInformationsRepository constructor.
     *
     * @param AccumulatedInformations $model
     */
    public function __construct(AccumulatedInformations $model)
    {
        $this->model = $model;
    }

    /**
     * @return AccumulatedInformations|\App\Models\Base|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new AccumulatedInformations();
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
     * @param integer $corpId
     * @param integer $demandId
     * @return \Illuminate\Database\Eloquent\Model|mixed|null|object|static
     */
    public function getInfos($corpId, $demandId)
    {
        return $this->model->where('corp_id', $corpId)->where('demand_id', $demandId)->first();
    }

    /**
     * @param integer $demandId
     * @return \Illuminate\Support\Collection
     */
    public function getAllInfos($demandId)
    {
        return $this->model->where('demand_id', $demandId)->get();
    }

    /**
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param integer $id
     * @param array $data
     * @return mixed
     */
    public function updateOrCreate($id, $data)
    {
        if (is_null($id)) {
            return $this->model->insert($data);
        } else {
            return $this->model->where('id', $id)->update($data);
        }
    }

    /**
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param integer $corpId
     * @param integer $demandId
     * @return mixed
     */
    public function getInfoByFlag($corpId, $demandId)
    {
        return $this->model->where('corp_id', $corpId)
            ->where('demand_id', $demandId)
            ->where("mail_open_flag", 0)
            ->get();
    }
}
