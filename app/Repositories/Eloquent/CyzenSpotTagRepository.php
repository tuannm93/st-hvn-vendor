<?php

namespace App\Repositories\Eloquent;

use App\Models\Cyzen\CyzenSpotTag;
use App\Repositories\CyzenSpotTagRepositoryInterface;

class CyzenSpotTagRepository extends SingleKeyModelRepository implements CyzenSpotTagRepositoryInterface
{
    /** @var CyzenSpotTag $model */
    protected $model;

    /**
     * CyzenSpotRepository constructor.
     * @param CyzenSpotTag $construct
     */
    public function __construct(CyzenSpotTag $construct)
    {
        $this->model = $construct;
    }

    /**
     * @param $spotId
     * @return mixed
     * @throws \Exception
     */
    public function deleteBySpotId($spotId)
    {
        return $this->model->where('spot_id', '=', $spotId)->delete();
    }

    /**
     * @param $data
     * @return mixed
     */
    public function saveData($data)
    {
        $tag = $this->model->where(['spot_tag_id' => $data['spot_tag_id'], 'group_id' => $data['group_id']])->first();

        if (empty($tag)) {
            return $this->model->insert($data);
        }

        foreach ($data as $key => $value) {
            $tag->$key = $value;
        }
        return $tag->save();
    }

    /**
     * @return \App\Models\Base|CyzenSpotTag|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new CyzenSpotTag();
    }

    /**
     * @param $key
     * @param CyzenSpotTag $model
     * @return mixed
     */
    public function checkForeignKey($key, $model)
    {
        $hasDataRelation = $model->find($key);

        return ($hasDataRelation) ? true : false;
    }

    /**
     * @return \App\Models\Cyzen\CyzenSpotTag
     */
    public function getModel()
    {
        return $this->model;
    }
}
