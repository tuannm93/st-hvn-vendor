<?php

namespace App\Repositories\Eloquent;

use App\Repositories\AgreementRepositoryInterface;
use App\Models\Agreement;

class AgreementRepository extends SingleKeyModelRepository implements AgreementRepositoryInterface
{
    /**
     * @var Agreement
     */
    protected $model;

    /**
     * AgreementRepository constructor.
     *
     * @param Agreement $model
     */
    public function __construct(Agreement $model)
    {
        $this->model = $model;
    }

    /**
     * @return Agreement|\App\Models\Base|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new Agreement();
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
     * get first agreement order by id descending
     * return object
     */
    public function getFirstAgreement()
    {
        return $this->model->orderBy('id', 'desc')->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function findCurrentVersion()
    {
        return $this->model->select('agreement.id as agreement_id', 'agreement.*', 'ap.*', 'api.*')->leftJoin('agreement_provisions as ap', 'ap.agreement_id', '=', 'agreement.id')
            ->leftJoin('agreement_provisions_item as api', 'api.agreement_provisions_id', '=', 'ap.id')
            ->orderBy('agreement.id', 'desc')
            ->orderBy('ap.sort_no', 'asc')
            ->orderBy('api.sort_no', 'asc')
            ->first();
    }

    /**
     * @param integer $id
     * @param array            $data
     * @return \App\Models\Base|bool|void
     */
    public function update($id, $data)
    {
        $this->model->where('id', '=', $id)->update($data);
    }

    /**
     * @param integer $id
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function findById($id)
    {
        return $this->model->where('id', '=', $id)->first();
    }
}
