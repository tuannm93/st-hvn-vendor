<?php
/**
 * Created by PhpStorm.
 * User: dungphamv
 * Date: 5/2/2018
 * Time: 9:57 AM
 */

namespace App\Repositories\Eloquent;

use App\Models\MAnswer;
use App\Repositories\MAnswerRepositoryInterface;

class MAnswerRepository extends SingleKeyModelRepository implements MAnswerRepositoryInterface
{
    /**
     * @var MAnswer
     */
    protected $model;

    /**
     * MAnswerRepository constructor.
     *
     * @param MAnswer $model
     */
    public function __construct(MAnswer $model)
    {
        $this->model = $model;
    }

    /**
     * @param integer $id
     * @param bool $toArray
     * @return array|\Illuminate\Support\Collection|mixed
     */
    public function dropDownAnswer($id, $toArray = false)
    {
        $query = $this->model->where('inquiry_id', $id)->pluck('answer_name', 'id');
        if ($toArray) {
            return $query->toArray();
        }
        return $query;
    }
}
