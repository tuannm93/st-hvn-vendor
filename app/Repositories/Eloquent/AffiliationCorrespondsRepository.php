<?php

namespace App\Repositories\Eloquent;

use App\Models\AffiliationCorrespond;
use App\Repositories\AffiliationCorrespondsRepositoryInterface;
use Auth;

class AffiliationCorrespondsRepository extends SingleKeyModelRepository implements AffiliationCorrespondsRepositoryInterface
{
    /**
     * @var AffiliationCorrespond
     */
    protected $model;

    /**
     * AffiliationCorrespondsRepository constructor.
     *
     * @param AffiliationCorrespond $model
     */
    public function __construct(AffiliationCorrespond $model)
    {
        $this->model = $model;
    }

    /**
     * @return AffiliationCorrespond|\App\Models\Base|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new AffiliationCorrespond();
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
     * Acquisition of member store correspondence history
     *
     * @param  array $conditions
     * @return \Illuminate\Support\Collection
     */
    public function getAffiliationCorrespond($conditions = [])
    {
        $results = $this->model->where($conditions)->orderBy('affiliation_corresponds.id', 'desc')->get();
        return $results;
    }

    /**
     * Update affiliation correspond with id
     *
     * @param integer $id
     * @param array $data
     * @return mixed|static
     */
    public function updateAffiliationCorrespondWithId($id, $data)
    {
        $update = $this->model->find($id);
        foreach ($data as $key => $value) {
            $update->$key = $value;
        }
        $update->modified = date('Y-m-d H:i:s');
        $update->modified_user_id = Auth::user()->user_id;
        $update->save();

        return $update;
    }
}
