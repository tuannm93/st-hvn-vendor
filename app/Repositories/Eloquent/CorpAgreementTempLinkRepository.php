<?php

namespace App\Repositories\Eloquent;

use App\Models\CorpAgreementTempLink;
use App\Repositories\CorpAgreementTempLinkRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CorpAgreementTempLinkRepository extends SingleKeyModelRepository implements CorpAgreementTempLinkRepositoryInterface
{
    /**
     * @var CorpAgreementTempLink
     */
    protected $model;

    /**
     * CorpAgreementTempLinkRepository constructor.
     *
     * @param CorpAgreementTempLink $model
     */
    public function __construct(CorpAgreementTempLink $model)
    {
        $this->model = $model;
    }

    /**
     * @return \App\Models\Base|CorpAgreementTempLink|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new CorpAgreementTempLink();
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
     * get by corp id and corp agreement id
     *
     * @param  integer $corpId
     * @param  integer $corpAgreementId
     * @return object
     */
    public function getItemByCorpIdAndCorpAgreementId($corpId, $corpAgreementId)
    {
        return $this->model->select(['id'])
            ->where('corp_id', $corpId)
            ->where('corp_agreement_id', $corpAgreementId)
            ->orderBy('id', 'desc')
            ->first();
    }

    /**
     * @param integer $corpId
     * @return mixed fist_mcorp
     */
    public function getTempLink($corpId)
    {
        $result = $this->model->leftJoin(
            'corp_agreement',
            'corp_agreement_temp_link.corp_agreement_id',
            '=',
            'corp_agreement.id'
        )
            ->where('corp_agreement_temp_link.corp_id', $corpId)
            ->orderBy('corp_agreement_temp_link.id', 'desc')
            ->select('corp_agreement_temp_link.*', 'corp_agreement.status')
            ->first();
        return $result;
    }

    /**
     * @param integer $corpId
     * @param integer $corpAgreementId
     * @return mixed
     */
    public function insertAgreementTempLink($corpId, $corpAgreementId)
    {
        $id = $this->model->insertGetId(
            [
                'corp_id'           => $corpId,
                'corp_agreement_id' => $corpAgreementId,
                'created'           => date('Y-m-d H:i:s'),
                'created_user_id'   => Auth::user()['user_id'],
                'modified'          => date('Y-m-d H:i:s'),
                'modified_user_id'  => Auth::user()['user_id'],
            ]
        );
        return $this->model->where('id', $id)->first();
    }

    /**
     * @param integer $corpId
     * @param integer $tempId
     * @return mixed
     */
    public function getFirstByCorpId($corpId, $tempId)
    {
        return $this->model->where(
            [
                ['corp_id', $corpId],
                ['id', '!=', $tempId]
            ]
        )
            ->orderBy('id', 'desc')
            ->select('id')
            ->first();
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function updateByTempLink($data)
    {
        unset($data['status']);
        return $this->model->where('id', $data['id'])->update($data);
    }

    /**
     * @param integer $corpId
     * @param integer $corpAgreementId
     * @return mixed
     */
    public function getByCorpIdAndCorpAgreementId($corpId, $corpAgreementId)
    {
        return $this->model->where(
            [
                ['corp_id', $corpId],
                ['corp_agreement_id', $corpAgreementId]
            ]
        )
            ->orderBy('id', 'desc')->first();
    }

    /**
     * @param integer $corpId
     * @param integer $corpAgreementId
     * @return mixed
     */
    public function getFirstByCorpIdAndCorpAgreementId($corpId, $corpAgreementId)
    {
        return $this->model->where(
            [
                ['corp_id', $corpId],
                ['corp_agreement_id', $corpAgreementId]
            ]
        )
            ->orderBy('id', 'desc')->first();
    }

    /**
     * @param integer $corpId
     * @return \Illuminate\Support\Collection
     */
    public function getByCorpIdWith2Record($corpId)
    {
        return $this->model->where(
            [
                ['corp_id', $corpId],
            ]
        )->orderBy('id', 'desc')->limit(2)->get();
    }

    /**
     * @param integer $corpId
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getFirstIdByCorpId($corpId)
    {
        $query = $this->model->where('corp_id', '=', $corpId)
            ->orderBy('id', 'desc')
            ->first();
        return $query;
    }

    /**
     * @param integer $corpId
     * @param integer $limit
     * @return array
     */
    public function getItemByCorpIdAndLimit($corpId, $limit)
    {
        $query = $this->model->where('corp_id', '=', $corpId)
            ->orderBy('id', 'desc')
            ->limit($limit)->get()->toArray();
        return $query;
    }

    /**
     * @param $corpId
     * @param string $orderBy
     * @return mixed
     */
    public function findLatestByCorpId($corpId, $orderBy = 'desc')
    {
        return $this->model->where(
            [
                ['corp_id', $corpId],
            ]
        )
            ->orderBy('id', $orderBy)
            ->first();
    }

    /**
     * @param integer $corpId
     * @param integer $corpAgreementId
     * @return int
     */
    public function insertAndGetIdBack($corpId, $corpAgreementId)
    {
        $id = $this->model->insertGetId(
            [
                'corp_id' => $corpId,
                'corp_agreement_id' => $corpAgreementId,
                'created' => Carbon::now(),
                'created_user_id' => Auth::user()['user_id'],
                'modified' => Carbon::now(),
                'modified_user_id' => Auth::user()['user_id'],
            ]
        );
        return $id;
    }

    /**
     * @param integer $cropId
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getByCropIdWithRelation($cropId)
    {
        return $this->model->with(["corpAgreement", "mCorpCategoriesTemps"])
            ->where("corp_id", $cropId)->orderBy("id", "desc")->first();
    }
}
