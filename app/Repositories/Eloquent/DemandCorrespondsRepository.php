<?php

namespace App\Repositories\Eloquent;

use App\Repositories\DemandCorrespondsRepositoryInterface;
use App\Models\DemandCorrespond;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DemandCorrespondsRepository extends SingleKeyModelRepository implements DemandCorrespondsRepositoryInterface
{
    /**
     * @var DemandCorrespond
     */
    protected $model;

    /**
     * DemandCorrespondsRepository constructor.
     *
     * @param DemandCorrespond $model
     */
    public function __construct(DemandCorrespond $model)
    {
        $this->model = $model;
    }

    /**
     * @return \App\Models\Base|DemandCorrespond|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new DemandCorrespond();
    }

    /**
     * @param \App\Models\Base $data
     * @return \App\Models\Base|bool|mixed
     */
    public function save($data)
    {
        if (isset($data['id'])) {
            $this->model = $this->model->where('id', $data['id'])->first();
        }
        $this->model->responders = $data['responders'];
        $this->model->corresponding_contens = $data['corresponding_contens'];
        $this->model->correspond_datetime = $data['correspond_datetime'];
        $this->model->modified = Carbon::now();
        $this->model->modified_user_id = Auth::user()->user_id;

        return $this->model->save();
    }

    /**
     * @param integer $id
     * @param array            $data
     * @return \App\Models\Base|bool
     */
    public function update($id, $data)
    {
        return $this->model->where('id', $id)->update($data);
    }

    /**
     * @param array $data
     * @return $this|\App\Models\Base|bool|\Illuminate\Database\Eloquent\Model
     */
    public function create($data)
    {
        return $this->model->create($data);
    }
}
