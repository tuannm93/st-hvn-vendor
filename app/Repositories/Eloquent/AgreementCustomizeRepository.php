<?php

namespace App\Repositories\Eloquent;

use App\Models\AgreementCustomize;
use App\Repositories\AgreementCustomizeRepositoryInterface;
use App\Helpers\Util;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AgreementCustomizeRepository extends SingleKeyModelRepository implements AgreementCustomizeRepositoryInterface
{

    /**
     * @var AgreementCustomize
     */
    protected $model;

    /**
     * AgreementCustomizeRepository constructor.
     *
     * @param AgreementCustomize $agreementCustomize
     */
    public function __construct(AgreementCustomize $agreementCustomize)
    {
        $this->model = $agreementCustomize;
    }

    /**
     * @return string
     */
    public function getModelClassName()
    {
        return get_class($this->model);
    }

    /**
     * @param integer $corpId
     * @param integer $deleteFlag
     * @return \Illuminate\Support\Collection
     */
    public function findAgreementCustomizeByCorpId($corpId, $deleteFlag)
    {
        return $this->model->where('corp_id', $corpId)->where('delete_flag', $deleteFlag)->orderBy('id', 'asc')->get();
    }

    /**
     * @return object
     */
    public function getAllAgreementCustomize()
    {
        $query = $this->model
            ->select(
                'agreement_customize.id',
                'm_corps.official_corp_name',
                'content',
                'sort_no',
                DB::raw(
                    "(CASE table_kind WHEN '" . AgreementCustomize::AGREEMENT_PROVISIONS . "' THEN '" . AgreementCustomize::TABLE_KIND_LABEL[AgreementCustomize::AGREEMENT_PROVISIONS] .
                    "' WHEN '" . AgreementCustomize::AGREEMENT_PROVISIONS_ITEM . "' THEN '" . AgreementCustomize::TABLE_KIND_LABEL[AgreementCustomize::AGREEMENT_PROVISIONS_ITEM] . "' ELSE '' END) AS table_kind"
                ),
                DB::raw(
                    "(CASE edit_kind WHEN '" . AgreementCustomize::ADD . "' THEN '" . AgreementCustomize::EDIT_KIND_LABEL[AgreementCustomize::ADD] .
                    "' WHEN '" . AgreementCustomize::UPDATE . "' THEN '" . AgreementCustomize::EDIT_KIND_LABEL[AgreementCustomize::UPDATE] .
                    "' WHEN '" . AgreementCustomize::DELETE . "' THEN '" . AgreementCustomize::EDIT_KIND_LABEL[AgreementCustomize::DELETE] . "' ELSE '' END) AS edit_kind"
                )
            )
            ->join('m_corps', 'm_corps.id', '=', 'agreement_customize.corp_id')
            ->groupBy(['agreement_customize.id', 'm_corps.official_corp_name']);
        return $query;
    }

    /**
     * @param integer $id
     * @return boolean|integer
     * result = count (*) where id = id
     */
    public function deleteById($id)
    {
        return $this->model->destroy($id);
    }

    /**
     * @param integer $id
     * @return object
     */
    public function findById($id)
    {
        return $this->model->where('id', $id)->first();
    }

    /**
     * @param array $data
     */
    public function saveAgreementCustomize($data)
    {
        $agreementCustomize = new AgreementCustomize();
        foreach ($data as $key => $value) {
            $agreementCustomize->$key = $value;
        }
        $agreementCustomize->create_date = Carbon::now()->toDateTimeString();
        $agreementCustomize->create_user_id = Auth::user()->id;
        $agreementCustomize->update_date = Carbon::now()->toDateTimeString();
        $agreementCustomize->update_user_id = Auth::user()->id;
        $agreementCustomize->save();
    }

    /**
     * @param integer $fieldId
     * @param  string $field
     * @param string $tableKind
     * @return mixed
     */
    public function findLastestCustomize($fieldId, $field, $tableKind)
    {
        return $this->model
            ->where($field, $fieldId)
            ->where('table_kind', $tableKind)
            ->orderBy('id', 'desc')->first();
    }

    /**
     * get by corp id and corp agreement id and table kind
     * @param  integer $corpId
     * @param  integer $agreementId
     * @return array
     */
    public function getByCorpIdAndCorpAgreementIdAndTableKind($corpId, $agreementId)
    {
        return $this->model
            ->where('corp_id', $corpId)
            ->where('corp_agreement_id', '<=', $agreementId)
            ->where('table_kind', 'AgreementProvisions')
            ->get()
            ->toArray();
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function agreementProvision()
    {
        return $this->model
            ->agreementProvision();
    }

    /**
     * find data terms
     * @param  integer $corpId
     * @param  integer $agreementId
     * @return array
     */
    public function findDataTerms($corpId, $agreementId)
    {
        return $this->model
            ->where('corp_id', $corpId)
            ->where('corp_agreement_id', '<=', $agreementId)
            ->where('table_kind', 'AgreementProvisionsItem')
            ->where(function ($query) {
                $query->orWhere('original_provisions_id', '!=', 0);
                $query->orWhere('original_item_id', '!=', 0);
            })
            ->orderBy('id', 'asc')
            ->get();
    }
}
