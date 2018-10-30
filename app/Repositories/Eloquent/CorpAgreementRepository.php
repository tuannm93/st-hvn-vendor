<?php

namespace App\Repositories\Eloquent;

use App\Models\CorpAgreement;
use App\Repositories\CorpAgreementRepositoryInterface;

class CorpAgreementRepository extends SingleKeyModelRepository implements CorpAgreementRepositoryInterface
{
    /**
     * @var CorpAgreement
     */
    protected $model;

    /**
     * CorpAgreementRepository constructor.
     *
     * @param CorpAgreement $model
     */
    public function __construct(CorpAgreement $model)
    {
        $this->model = $model;
    }

    /**
     * @return \App\Models\Base|CorpAgreement|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new CorpAgreement();
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
     * get status message
     *
     * @return array
     */
    public function getStatusMessage()
    {
        return $this->model->getStatusMessage();
    }

    /**
     * count corp agreement by corp id
     *
     * @param  integer $corpId
     * @return integer
     */
    public function getCountByCorpIdAndStatus($corpId, $status = ['Complete', 'Application'])
    {
        return $this->model
            ->where('corp_id', $corpId)
            ->whereNotIn('status', $status)
            ->count();
    }

    /**
     * get corp agreement by crop id and agreement id
     *
     * @param  integer $corpId
     * @param  integer $agreementId
     * @param  boolean $isLastCorpId
     * @return object agreement
     */
    public function getFirstByCorpIdAndAgreementId($corpId, $agreementId = null, $isLastCorpId = false)
    {
        $result = $this->model->where('corp_id', $corpId);

        if ($agreementId != null) {
            $result = $result->where('id', $agreementId);
        }

        if ($isLastCorpId) {
            $result = $result->orderBy('id', 'desc');
        }

        return $result->first();
    }

    /**
     * get all corp argeement by crop id
     *
     * @param  integer $corpId
     * @param  string $orderBy
     * @return object agreement
     */
    public function getAllByCorpId($corpId, $orderBy = 'desc')
    {
        return $this->model
            ->where('corp_id', $corpId)
            ->orderBy('id', $orderBy)
            ->get();
    }

    /**
     * find by crop id and status
     *
     * @param  integer         $corpId
     * @param  string or array $status
     * @return object
     */
    public function findByCorpIdAndStatus($corpId, $status)
    {
        $condition = $this->model
            ->where('corp_id', $corpId);
        if (is_array($status)) {
            $condition = $condition->whereIn('status', $status);
        } else {
            $condition = $condition->where('status', $status);
        }

        return $condition->orderBy('id', 'desc')
            ->first();
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function createNewCorpAgreement($data)
    {
        $id = $this->model->insertGetId($data);
        return $this->model->where('id', $id)->first();
    }

    /**
     * @param integer $corpId
     * @return mixed
     */
    public function findByCorpId($corpId)
    {
        $query = $this->model->where('corp_id', $corpId)
            ->orderBy('id', 'DESC');
        return $query->first();
    }

    /**
     * @param integer $corpId
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function findByCorpIdAndStatusCompleteAndNotNullAcceptationDate($corpId)
    {
        $query = $this->model->where('corp_id', $corpId)
            ->where('status', CorpAgreement::COMPLETE)
            ->whereNotNull('acceptation_date')
            ->orderBy('id', 'DESC');
        return $query->first();
    }
}
