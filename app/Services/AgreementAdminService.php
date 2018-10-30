<?php

namespace App\Services;

use App\Models\AffiliationInfo;
use App\Models\CorpAgreement;
use App\Models\MCorp;
use Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use App\Repositories\AffiliationInfoRepositoryInterface;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AgreementAdminService
{
    /**
     * @var AffiliationInfoRepositoryInterface
     */
    protected $affiliationInfoRepository;

    /**
     * AgreementAdminService constructor.
     *
     * @param AffiliationInfoRepositoryInterface $affiliationInfoRepository
     */
    public function __construct(AffiliationInfoRepositoryInterface $affiliationInfoRepository)
    {
        $this->affiliationInfoRepository = $affiliationInfoRepository;
    }

    /**
     * @param Request  $request
     * @param $fileType
     * @throws \Exception
     */
    public function exportFile(Request $request, $fileType)
    {
        set_time_limit(0);
        $fileName = Lang::get('agreement_admin_dashboard.contractStatusList');
        $data = [];

        $items = $this->getTableDataWithCondition($request);
        $dataObject = $items->getData();
        $dataArray = $dataObject->{'data'};

        foreach ($dataArray as $key => $value) {
            $data[$key]['corp_id'] = $value->corp_id;
            $data[$key]['customize_label'] = $value->customize_label;
            $data[$key]['corp_kind'] = $value->corp_kind;
            $data[$key]['agreement_status'] = $value->agreement_status;
            $data[$key]['official_corp_name'] = $value->official_corp_name;
            $data[$key]['corp_name'] = $value->corp_name;
            $data[$key]['listed_kind'] = $value->listed_kind;
            $data[$key]['capital_stock'] = $value->capital_stock;

            if ($value->corp_agreement_id == null || $value->hansha_check == null) {
                $antiCompanyCheck = CorpAgreement::HANSHA_CHECK_STATUS[CorpAgreement::NONE];
            } else {
                $antiCompanyCheck = CorpAgreement::HANSHA_CHECK_STATUS[$value->hansha_check];
            }
            if (!empty($value->hansha_check_user_name)) {
                $antiCompanyCheck .= $value->hansha_check_user_name;
            }
            if (!empty($value->hansha_check_date)) {
                $antiCompanyCheck .= $value->hansha_check_date;
            }
            $data[$key]['hansha_check'] = $antiCompanyCheck;

            $specialCommercialLawCheck = $value->transactions_law_user_name;
            if (!empty($value->transactions_law_date)) {
                $specialCommercialLawCheck .= $value->transactions_law_date;
            }
            $data[$key]['transactions_law_date'] = $specialCommercialLawCheck;

            $data[$key]['link'] = $value->affilication_detail_url;
        }

        $dataHeader = [
            Lang::get('agreement_admin_dashboard.companyId'),
            Lang::get('agreement_admin_dashboard.rider'),
            Lang::get('agreement_admin_dashboard.companyType'),
            Lang::get('agreement_admin_dashboard.contractStatus'),
            Lang::get('agreement_admin_dashboard.officialCompanyName'),
            Lang::get('agreement_admin_dashboard.companyInfo'),
            Lang::get('agreement_admin_dashboard.listing'),
            Lang::get('agreement_admin_dashboard.capital'),
            Lang::get('agreement_admin_dashboard.antiCompanyCheck'),
            Lang::get('agreement_admin_dashboard.specialCommercialLawCheck'),
            Lang::get('agreement_admin_dashboard.detail')
        ];

        Excel::create(
            $fileName,
            function ($excel) use ($data, $dataHeader) {
                $excel->sheet(
                    'Sheet 1',
                    function ($sheet) use ($data, $dataHeader) {

                        $sheet->row(1, $dataHeader);
                        $sheet->fromArray($data, null, 'A2', false, false);
                    }
                );
            }
        )->export($fileType);
    }

    /**
     * @param Request $request
     * @param $customizeLabel
     * @param $corpKind
     * @param $agreementStatus
     * @param $listedKind
     * @param $query
     */
    private function setSearchAllAndSortCondition(Request $request, $customizeLabel, $corpKind, $agreementStatus, $listedKind, &$query)
    {
        // check sort condition
        if (validateStringIsNullOrEmpty($request->input('order.0.column'))) {
            $query = $query->orderBy('t1.corp_id', 'asc');
        }

        // search all text box
        if (!validateStringIsNullOrEmpty($request->input('search.value'))) {
            $searchValue = "%{$request->input('search.value')}%";
            $sql = "(CAST(t1.corp_id as TEXT) like ? OR
                    LOWER($customizeLabel) LIKE LOWER(?) OR
                    LOWER($corpKind) LIKE LOWER(?) OR
                    LOWER($agreementStatus) like LOWER(?) OR
                    LOWER(t0.official_corp_name) like LOWER(?) OR
                    LOWER(t0.corp_name) like LOWER(?) OR
                    LOWER($listedKind) like LOWER(?) OR
                    t1.capital_stock like ? OR
                    LOWER(u1.user_name) like LOWER(?) OR
                    LOWER(u2.user_name) like LOWER(?))";
            $query = $query->havingRaw($sql, [$searchValue, $searchValue, $searchValue, $searchValue, $searchValue,
                $searchValue, $searchValue, $searchValue, $searchValue, $searchValue]);
        }
    }

    /**
     * @param Request $request
     * @param $customizeLabel
     * @param $corpKind
     * @param $agreementStatus
     * @param $query
     */
    private function setColumnSearchConditionFrom0To4(Request $request, $customizeLabel, $corpKind, $agreementStatus, &$query)
    {
        if (!validateStringIsNullOrEmpty($request->input('columns.0.search.value'))) {
            $columnSearchValue = $request->input('columns.0.search.value');
            $query = $query->havingRaw("CAST(t1.corp_id as TEXT) like ?", ["%{$columnSearchValue}%"]);
        }
        if (!validateStringIsNullOrEmpty($request->input('columns.1.search.value'))) {
            $columnSearchValue = $request->input('columns.1.search.value');
            $query = $query->havingRaw("LOWER(" . $customizeLabel . ") like LOWER(?)", ["%{$columnSearchValue}%"]);
        }
        if (!validateStringIsNullOrEmpty($request->input('columns.2.search.value'))) {
            $columnSearchValue = $request->input('columns.2.search.value');
            $query = $query->havingRaw("LOWER(" . $corpKind . ") like LOWER(?)", ["%{$columnSearchValue}%"]);
        }
        if (!validateStringIsNullOrEmpty($request->input('columns.3.search.value'))) {
            $columnSearchValue = $request->input('columns.3.search.value');
            $query = $query->havingRaw("LOWER(" . $agreementStatus . ") like LOWER(?)", ["%{$columnSearchValue}%"]);
        }
        if (!validateStringIsNullOrEmpty($request->input('columns.4.search.value'))) {
            $columnSearchValue = $request->input('columns.4.search.value');
            $query = $query->havingRaw("LOWER(t0.official_corp_name) like LOWER(?)", ["%{$columnSearchValue}%"]);
        }
    }

    /**
     * @param Request $request
     * @param $listedKind
     * @param $query
     */
    private function setColumnSearchConditionFrom5To9(Request $request, $listedKind, &$query)
    {
        if (!validateStringIsNullOrEmpty($request->input('columns.5.search.value'))) {
            $columnSearchValue = $request->input('columns.5.search.value');
            $query = $query->havingRaw("LOWER(t0.corp_name) like LOWER(?)", ["%{$columnSearchValue}%"]);
        }
        if (!validateStringIsNullOrEmpty($request->input('columns.6.search.value'))) {
            $columnSearchValue = $request->input('columns.6.search.value');
            $query = $query->havingRaw("LOWER(" . $listedKind . ") = LOWER(?)", ["{$columnSearchValue}"]); // exactly search
        }
        if (!validateStringIsNullOrEmpty($request->input('columns.7.search.value'))) {
            $columnSearchValue = $request->input('columns.7.search.value');
            $query = $query->havingRaw("t1.capital_stock ?", ["%{$columnSearchValue}%"]);
        }
        if (!validateStringIsNullOrEmpty($request->input('columns.8.search.value'))) {
            $columnSearchValue = $request->input('columns.8.search.value');
            $query = $query->havingRaw("LOWER(u1.user_name) like LOWER(?)", ["%{$columnSearchValue}%"]);
        }
        if (!validateStringIsNullOrEmpty($request->input('columns.9.search.value'))) {
            $columnSearchValue = $request->input('columns.9.search.value');
            $query = $query->havingRaw("LOWER(u2.user_name) like LOWER(?)", ["%{$columnSearchValue}%"]);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function getTableDataWithCondition(Request $request)
    {
        $customizeLabel = "CASE WHEN t3.corp_id IS NOT NULL THEN '" . trans('agreement_admin_dashboard.rider'). "' ELSE NULL END";
        $corpKind = "CASE t0.corp_kind"
            . " WHEN '" . MCorp::CORP . "' THEN '" . MCorp::CORP_KIND[MCorp::CORP]
            . "' WHEN '" . MCorp::PERSON . "' THEN '" . MCorp::CORP_KIND[MCorp::PERSON] . "' END";
        $agreementStatus = "CASE WHEN t2.status IS NULL THEN '" . CorpAgreement::AGREEMENT_STATUS[CorpAgreement::STEP0]
            . "' WHEN t2.status = '" . CorpAgreement::STEP0 . "' THEN '" . CorpAgreement::AGREEMENT_STATUS[CorpAgreement::STEP0]
            . "' WHEN t2.status = '" . CorpAgreement::STEP1 . "' THEN '" . CorpAgreement::AGREEMENT_STATUS[CorpAgreement::STEP1]
            . "' WHEN t2.status = '" . CorpAgreement::STEP2 . "' THEN '" . trans('agreement_admin_dashboard.STEP2_KIND')
            . "' WHEN t2.status = '" . CorpAgreement::STEP3 . "' THEN '" . trans('agreement_admin_dashboard.STEP3_KIND')
            . "' WHEN t2.status = '" . CorpAgreement::STEP4 . "' THEN '" . trans('agreement_admin_dashboard.STEP4_KIND')
            . "' WHEN t2.status = '" . CorpAgreement::STEP5 . "' THEN '" . trans('agreement_admin_dashboard.STEP5_KIND')
            . "' WHEN t2.status = '" . CorpAgreement::STEP6 . "' THEN '" . trans('agreement_admin_dashboard.STEP6_KIND')
            . "' WHEN t2.status = '" . CorpAgreement::CONFIRM . "' THEN '" . CorpAgreement::AGREEMENT_STATUS[CorpAgreement::CONFIRM]
            . "' WHEN t2.status = '" . CorpAgreement::REVIEW . "' THEN '" . CorpAgreement::AGREEMENT_STATUS[CorpAgreement::REVIEW]
            . "' WHEN t2.status = '" . CorpAgreement::PASS_BACK . "' THEN '" . CorpAgreement::AGREEMENT_STATUS[CorpAgreement::PASS_BACK]
            . "' WHEN t2.status = '" . CorpAgreement::COMPLETE . "' THEN '" . CorpAgreement::AGREEMENT_STATUS[CorpAgreement::COMPLETE]
            . "' WHEN t2.status = '" . CorpAgreement::NOT_SIGNED . "' THEN '" . CorpAgreement::AGREEMENT_STATUS[CorpAgreement::NOT_SIGNED]
            . "' WHEN t2.status = '" . CorpAgreement::RECONFIRMATION . "' THEN '" . trans('agreement_admin_dashboard.RECONFIRMATION_KIND')
            . "' WHEN t2.status = '" . CorpAgreement::RESIGNING . "' THEN '" . CorpAgreement::AGREEMENT_STATUS[CorpAgreement::RESIGNING]
            . "' WHEN t2.status = '" . CorpAgreement::APPLICATION . "' THEN '" . CorpAgreement::AGREEMENT_STATUS[CorpAgreement::APPLICATION] . "' END";
        $listedKind = "CASE t1.listed_kind"
            . " WHEN '" . AffiliationInfo::LISTED . "' THEN '" . AffiliationInfo::LISTED_KIND[AffiliationInfo::LISTED]
            . "' WHEN '" . AffiliationInfo::UNLISTED . "' THEN '" . AffiliationInfo::LISTED_KIND[AffiliationInfo::UNLISTED] . "' END";

        $query = DB::table('affiliation_infos as t1')
        ->select(
            't1.corp_id as corp_id',
            DB::raw($customizeLabel . " AS customize_label"),
            DB::raw($corpKind . " AS corp_kind"),
            DB::raw($agreementStatus . " AS agreement_status"),
            't0.official_corp_name as official_corp_name',
            't0.corp_name as corp_name',
            DB::raw($listedKind . " AS listed_kind"),
            't1.capital_stock as capital_stock',
            'u1.user_name AS hansha_check_user_name',
            'u2.user_name AS transactions_law_user_name',
            't2.id AS corp_agreement_id',
            't2.hansha_check AS hansha_check',
            DB::raw('to_char(t2.hansha_check_date, \'YYYY/MM/DD HH24:MI\') as hansha_check_date'),
            DB::raw('to_char(t2.transactions_law_date, \'YYYY/MM/DD HH24:MI\') as transactions_law_date')
        )
        ->join('m_corps as t0', 't1.corp_id', '=', 't0.id')
        ->leftJoin('corp_agreement as t2', function ($join) {
            $join->on('t2.corp_id', '=', 't1.corp_id')
                ->on('t2.id', '=', DB::raw("
                        (SELECT MAX(t4.id)
                        FROM m_corps as t5, corp_agreement as t4, m_corps as t6
                        WHERE (((t5.id = t6.id)
                          AND (t4.delete_flag = false))
                          AND ((t5.id = t4.corp_id)
                          AND (t6.id = t2.corp_id))))
                      "));
        })
        ->leftJoin('agreement_customize as t3', 't3.corp_id', '=', 't1.corp_id')
        ->leftJoin('m_users as u1', 't2.hansha_check_user_id', '=', 'u1.user_id')
        ->leftJoin('m_users as u2', 't2.transactions_law_user_id', '=', 'u2.user_id')
        ->groupBy(['t1.corp_id', 't3.corp_id', 't0.corp_kind', 't2.status', 't0.official_corp_name', 't0.corp_name',
            't1.listed_kind', 't1.capital_stock', 't2.id', 'u1.user_name', 'u2.user_name', 't0.agreement_target_flag',
            't0.affiliation_status', 't0.del_flg'])
        ->having('t0.agreement_target_flag', true)
        ->having('t0.affiliation_status', 1)
        ->having('t0.del_flg', 0)
        ->distinct();

        $this->setSearchAllAndSortCondition($request, $customizeLabel, $corpKind, $agreementStatus, $listedKind, $query);
        $this->setColumnSearchConditionFrom0To4($request, $customizeLabel, $corpKind, $agreementStatus, $query);
        $this->setColumnSearchConditionFrom5To9($request, $listedKind, $query);
        $customSort = [4, 5];

        $result = DataTables::of($query)
            ->filterColumn('corp_id', function ($query) {
            })
            ->filterColumn('customize_label', function ($query) {
            })
            ->filterColumn('corp_kind', function ($query) {
            })
            ->filterColumn('agreement_status', function ($query) {
            })
            ->filterColumn('official_corp_name', function ($query) {
            })
            ->filterColumn('corp_name', function ($query) {
            })
            ->filterColumn('listed_kind', function ($query) {
            })
            ->filterColumn('capital_stock', function ($query) {
            })
            ->filterColumn('hansha_check_user_name', function ($query) {
            })
            ->filterColumn('transactions_law_user_name', function ($query) {
            })
            ->addColumn('agreement_customize_with_corp_url', function ($query) {
                return route('agreement.customize.with.corp', ['id' => $query->corp_id]);
            })
            ->addColumn('affilication_detail_url', function ($query) {
                return route('affiliation.detail.edit', ['id' => $query->corp_id]);
            });

        if (in_array($request->input('order.0.column'), $customSort)) {
            $result->order(function ($query) use ($request) {
                $this->orderData($request->input('order.0.column'), $request->input('order.0.dir'), $query);
            });
        }

        return $result->make(true);
    }

    /**
     * @return array
     */
    public function getAgreementStatusItemLabel()
    {
        $agreementStatusItemLabel = [
            null => '',
            CorpAgreement::STEP0 => CorpAgreement::AGREEMENT_STATUS[CorpAgreement::STEP0],
            CorpAgreement::STEP1 => CorpAgreement::AGREEMENT_STATUS[CorpAgreement::STEP1],
            CorpAgreement::STEP2 => Lang::get('agreement_admin_dashboard.STEP2_KIND'),
            CorpAgreement::STEP3 => Lang::get('agreement_admin_dashboard.STEP3_KIND'),
            CorpAgreement::STEP4 => Lang::get('agreement_admin_dashboard.STEP4_KIND'),
            CorpAgreement::STEP5 => Lang::get('agreement_admin_dashboard.STEP5_KIND'),
            CorpAgreement::STEP6 => Lang::get('agreement_admin_dashboard.STEP6_KIND'),
            CorpAgreement::CONFIRM => CorpAgreement::AGREEMENT_STATUS[CorpAgreement::CONFIRM],
            CorpAgreement::APPLICATION => CorpAgreement::AGREEMENT_STATUS[CorpAgreement::APPLICATION],
            CorpAgreement::REVIEW => CorpAgreement::AGREEMENT_STATUS[CorpAgreement::REVIEW],
            CorpAgreement::PASS_BACK => CorpAgreement::AGREEMENT_STATUS[CorpAgreement::PASS_BACK],
            CorpAgreement::COMPLETE => CorpAgreement::AGREEMENT_STATUS[CorpAgreement::COMPLETE],
            CorpAgreement::RECONFIRMATION => Lang::get('agreement_admin_dashboard.RECONFIRMATION_KIND'),
            CorpAgreement::NOT_SIGNED => CorpAgreement::AGREEMENT_STATUS[CorpAgreement::NOT_SIGNED],
            CorpAgreement::RESIGNING => CorpAgreement::AGREEMENT_STATUS[CorpAgreement::RESIGNING]
        ];

        return $agreementStatusItemLabel;
    }

    /**
     * @param $column
     * @param $orderBy
     * @param $query
     */
    private function orderData($column, $orderBy, &$query)
    {
        switch ($column) {
            case 4:
                $query->addSelect(DB::raw('trim(st_full2half(t0.official_corp_name)) AS official_corp_name_1'))
                      ->orderBy('official_corp_name_1', $orderBy);
                break;
            case 5:
                $query->addSelect(DB::raw('trim(st_full2half(t0.corp_name)) AS corp_name_1'))
                      ->orderBy('corp_name_1', $orderBy);
                break;
        }
    }
}
