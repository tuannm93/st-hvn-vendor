<?php

namespace App\Repositories\Eloquent;

use App\Models\MCorpNewYear;
use App\Repositories\MCorpNewYearRepositoryInterface;

/**
 * Class MCorpNewYearRepository
 *
 * @package App\Repositories\Eloquent
 */
class MCorpNewYearRepository extends SingleKeyModelRepository implements MCorpNewYearRepositoryInterface
{
    /**
     *
     */
    const NEW_YEAR_STATUS_OPTIONS = [
        '稼働' => '稼働',
        'TELのみ' => 'TELのみ',
        '休み' => '休み',
    ];

    /**
     * @var MCorpNewYear
     */
    private $model;

    /**
     * MCorpNewYearRepository constructor.
     *
     * @param MCorpNewYear $model
     */
    public function __construct(MCorpNewYear $model)
    {
        $this->model = $model;
    }

    /**
     * @return \App\Models\Base|MCorpNewYear|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new MCorpNewYear();
    }

    /**
     * @return bool|mixed|null
     * @throws \Exception
     */
    public function deleteAll()
    {
        return $this->model->whereIn(
            "id",
            function ($query) {
                $query->select("m_corp_new_years.id")->from($this->model->getTable())->join(
                    "m_corps",
                    function ($q) {
                        $q->on("m_corp_new_years.corp_id", "=", "m_corps.id")->where("m_corps.del_flg", 0);
                    }
                );
            }
        )->delete();
    }

    /**
     * @param integer $corpId
     * @param array $data
     * @return bool|mixed
     */
    public function updateNewYear($corpId, $data)
    {
        if ($corpId) {
            $newYear = $this->model->where('corp_id', $corpId)->first();
        } else {
            $newYear = null;
        }

        if (! $newYear) {
            $newYear = $this->getBlankModel();
            $newYear->created = date('Y-m-d H:i:s');
        }

        $newYear->modified = date('Y-m-d H:i:s');
        foreach ($data as $key => $value) {
            $newYear->$key = $value;
        }

        $newYear->save();

        return ($newYear) ? true : false;
    }

    /**
     * @param integer $mCorpId
     * @param array   $fields
     * @return mixed
     */
    public function getItemByMCorpId($mCorpId, $fields = ['*'])
    {
        return $this->model->where('corp_id', '=', $mCorpId)->get($fields)->first();
    }
}
