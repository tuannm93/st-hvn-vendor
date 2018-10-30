<?php


namespace App\Services;

use App\Repositories\AgreementCustomizeRepositoryInterface;
use App\Repositories\AgreementProvisionsItemRepositoryInterface;
use App\Repositories\AgreementProvisionsRepositoryInterface;
use App\Repositories\AgreementRepositoryInterface;
use App\Repositories\CorpAgreementRepositoryInterface;
use Carbon\Carbon;
use App\Models\AgreementCustomize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Yajra\DataTables\DataTables;

class AgreementCustomizeService
{
    /**
     * @var AgreementCustomizeRepositoryInterface
     */
    protected $agreementCustomizeRepository;

    /**
     * @var $corpAgreementRepository
     */
    protected $corpAgreementRepository;

    /**
     * @var $agreementRepository
     */
    protected $agreementRepository;

    /**
     * @var $agreementProvisionsRepository
     */
    protected $agreementProvisionsRepository;

    /**
     * @var AgreementProvisionsItemRepositoryInterface
     */
    protected $agreementProvisionsItemRepo;


    /**
     * AgreementCustomizeService constructor.
     * @param AgreementCustomizeRepositoryInterface $agreementCustomizeRepository
     * @param CorpAgreementRepositoryInterface $corpAgreementRepository
     * @param AgreementRepositoryInterface $agreementRepository
     * @param AgreementProvisionsRepositoryInterface $agreementProvisionsRepository
     * @param AgreementProvisionsItemRepositoryInterface $agreementProvisionsItemRepo
     */
    public function __construct(
        AgreementCustomizeRepositoryInterface $agreementCustomizeRepository,
        CorpAgreementRepositoryInterface $corpAgreementRepository,
        AgreementRepositoryInterface $agreementRepository,
        AgreementProvisionsRepositoryInterface $agreementProvisionsRepository,
        AgreementProvisionsItemRepositoryInterface $agreementProvisionsItemRepo
    ) {
        $this->agreementCustomizeRepository = $agreementCustomizeRepository;
        $this->corpAgreementRepository = $corpAgreementRepository;
        $this->agreementRepository = $agreementRepository;
        $this->agreementProvisionsRepository = $agreementProvisionsRepository;
        $this->agreementProvisionsItemRepo = $agreementProvisionsItemRepo;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteAgreementCustomize($id)
    {
        return $this->agreementCustomizeRepository->deleteById($id);
    }

    /**
     * @param $id
     * @param $data
     */
    public function updateById($id, $data)
    {
        $agreementCustomize = $this->agreementCustomizeRepository->findById($id);
        $agreementCustomize->fill($data);
        $agreementCustomize->version_no = $agreementCustomize->version_no + 1;
        $agreementCustomize->update_date = Carbon::now()->toDateTimeString();
        $agreementCustomize->update_user_id = Auth::user()->id;
        $this->agreementCustomizeRepository->save($agreementCustomize);
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function getAllAgreementCustomize(Request $request)
    {
        $query = $this->agreementCustomizeRepository->getAllAgreementCustomize();

        if (validateStringIsNullOrEmpty($request->input('order.0.column'))) {
            $query = $query->orderBy('id', 'asc');
        }

        // search all test box
        if (!validateStringIsNullOrEmpty($request->input('search.value'))) {
            $searchValue = "%{$request->input('search.value')}%";
            $sql = "(LOWER(m_corps.official_corp_name) like LOWER(?) OR
                    LOWER(CASE table_kind WHEN '" . AgreementCustomize::AGREEMENT_PROVISIONS . "' THEN '" . AgreementCustomize::TABLE_KIND_LABEL[AgreementCustomize::AGREEMENT_PROVISIONS] .
                "' WHEN '" . AgreementCustomize::AGREEMENT_PROVISIONS_ITEM . "' THEN '" . AgreementCustomize::TABLE_KIND_LABEL[AgreementCustomize::AGREEMENT_PROVISIONS_ITEM] . "' ELSE '' END) LIKE LOWER(?) OR
                    LOWER(CASE edit_kind WHEN '" . AgreementCustomize::ADD . "' THEN '" . AgreementCustomize::EDIT_KIND_LABEL[AgreementCustomize::ADD] .
                "' WHEN '" . AgreementCustomize::UPDATE . "' THEN '" . AgreementCustomize::EDIT_KIND_LABEL[AgreementCustomize::UPDATE] .
                "' WHEN '" . AgreementCustomize::DELETE . "' THEN '" . AgreementCustomize::EDIT_KIND_LABEL[AgreementCustomize::DELETE] . "' ELSE '' END) LIKE LOWER(?) OR
                    LOWER(agreement_customize.content) like LOWER(?) OR
                    CAST(agreement_customize.sort_no as TEXT) like ?)";
            $query = $query->havingRaw($sql, [$searchValue, $searchValue, $searchValue, $searchValue, $searchValue]);
        }

        // search textboxs in table's headers
        if (!validateStringIsNullOrEmpty($request->input('columns.0.search.value'))) {
            $officialCorpNameSearchValue = $request->input('columns.0.search.value');
            $sql = "LOWER(m_corps.official_corp_name) like LOWER(?)";
            $query = $query->havingRaw($sql, ["%{$officialCorpNameSearchValue}%"]);
        }
        if (!validateStringIsNullOrEmpty($request->input('columns.1.search.value'))) {
            $tableKindSearchValue = $request->input('columns.1.search.value');
            $sql = "LOWER(CASE table_kind WHEN '" . AgreementCustomize::AGREEMENT_PROVISIONS . "' THEN '" . AgreementCustomize::TABLE_KIND_LABEL[AgreementCustomize::AGREEMENT_PROVISIONS] .
                "' WHEN '" . AgreementCustomize::AGREEMENT_PROVISIONS_ITEM . "' THEN '" . AgreementCustomize::TABLE_KIND_LABEL[AgreementCustomize::AGREEMENT_PROVISIONS_ITEM] . "' ELSE '' END) LIKE LOWER(?)";
            $query = $query->havingRaw($sql, ["%{$tableKindSearchValue}%"]);
        }
        if (!validateStringIsNullOrEmpty($request->input('columns.2.search.value'))) {
            $editKindSearchValue = $request->input('columns.2.search.value');
            $sql = "LOWER(CASE edit_kind WHEN '" . AgreementCustomize::ADD . "' THEN '" . AgreementCustomize::EDIT_KIND_LABEL[AgreementCustomize::ADD] .
                "' WHEN '" . AgreementCustomize::UPDATE . "' THEN '" . AgreementCustomize::EDIT_KIND_LABEL[AgreementCustomize::UPDATE] .
                "' WHEN '" . AgreementCustomize::DELETE . "' THEN '" . AgreementCustomize::EDIT_KIND_LABEL[AgreementCustomize::DELETE] . "' ELSE '' END) LIKE LOWER(?)";
            $query = $query->havingRaw($sql, ["%{$editKindSearchValue}%"]);
        }
        if (!validateStringIsNullOrEmpty($request->input('columns.3.search.value'))) {
            $contentSearchValue = $request->input('columns.3.search.value');
            $sql = "LOWER(agreement_customize.content) like LOWER(?)";
            $query = $query->havingRaw($sql, ["%{$contentSearchValue}%"]);
        }
        if (!validateStringIsNullOrEmpty($request->input('columns.4.search.value'))) {
            $sortNoSearchValue = $request->input('columns.4.search.value');
            $sql = "CAST(agreement_customize.sort_no as TEXT) like ?";
            $query = $query->havingRaw($sql, ["%{$sortNoSearchValue}%"]);
        }

        $datatableQuery = DataTables::of($query)
            ->filterColumn(
                'official_corp_name',
                function ($query) {
                }
            )
            ->filterColumn(
                'table_kind',
                function ($query) {
                }
            )
            ->filterColumn(
                'edit_kind',
                function ($query) {
                }
            )
            ->filterColumn(
                'content',
                function ($query) {
                }
            )
            ->filterColumn(
                'sort_no',
                function ($query) {
                }
            )
            ->addColumn(
                'delete_url',
                function ($query) {
                    return route('agreement.customize.delete', ['id' => $query->id]);
                }
            )
            ->addColumn(
                'update_url',
                function ($query) {
                    return route('agreement.customize.update', ['id' => $query->id]);
                }
            );

        return $datatableQuery->make(true);
    }

    /**
     * @param $data
     */
    public function updateAgreementCustomizeProvisionWithCorp($data)
    {
        $customizeFlag = $data['customize_flag'];
        unset($data['customize_flag']);
        $id = $data['id'];
        $id = str_replace("c", "-", $id);
        unset($data['id']);

        $agreementCustomize = $this->getCustomizeObject($data, $id);
        $agreementCustomize->table_kind = AgreementCustomize::AGREEMENT_PROVISIONS;

        if ($customizeFlag == "true") {
            if ($id < 0) {
                $agreementCustomizeOldVersion = $this->agreementCustomizeRepository->findById(0 - $id);
                $agreementCustomize->original_provisions_id = $agreementCustomizeOldVersion->original_provisions_id;
                $agreementCustomize->customize_provisions_id = $agreementCustomizeOldVersion->customize_provisions_id;
            } else {
                $agreementCustomize->original_provisions_id = $id;
                $agreementCustomize->customize_provisions_id = 0;
            }
        } else {
            if ($id > 0) {
                $agreementCustomize->original_provisions_id = $id;
            } else {
                $agreementCustomize->original_provisions_id = 0;
            }
            $agreementCustomize->customize_provisions_id = 0;
        }
        $agreementCustomize->original_item_id = 0;
        $agreementCustomize->customize_item_id = 0;

        $agreementCustomize = $this->agreementCustomizeRepository->save($agreementCustomize);
        if ($data['edit_kind'] === AgreementCustomize::ADD) {
            $agreementCustomize->version_no = $agreementCustomize->version_no + 1;
            $agreementCustomize->customize_provisions_id = $agreementCustomize->id;
            $agreementCustomize->update_date = Carbon::now()->toDateTimeString();
            $agreementCustomize->update_user_id = Auth::user()->id;
            $this->agreementCustomizeRepository->save($agreementCustomize);

            return Lang::get('agreement_admin.registration_complete');
        }

        return Lang::get('agreement_admin.content_update_successfully');
    }

    /**
     * @param $data
     */
    public function updateAgreementCustomizeItemWithCorp($data)
    {
        $customizeFlag = $data['customize_flag'];
        unset($data['customize_flag']);
        $id = $data['id'];
        $id = str_replace("c", "-", $id);
        unset($data['id']);
        $provisionId = $data['provision_id'];
        unset($data['provision_id']);

        $agreementCustomize = $this->getCustomizeObject($data, $id);
        $agreementCustomize->table_kind = AgreementCustomize::AGREEMENT_PROVISIONS_ITEM;

        if ($customizeFlag == "true") {
            if ($id < 0) {
                $agreementCustomizeOldVersion = $this->agreementCustomizeRepository->findById(0 - $id);
                $agreementCustomize->original_provisions_id = $agreementCustomizeOldVersion->original_provisions_id;
                $agreementCustomize->customize_provisions_id = $agreementCustomizeOldVersion->customize_provisions_id;
                $agreementCustomize->original_item_id = $agreementCustomizeOldVersion->original_item_id;
                $agreementCustomize->customize_item_id = $agreementCustomizeOldVersion->customize_item_id;
            } elseif ($id > 0) {
                $agreementOldVersion = $this->agreementProvisionsItemRepo->findById($id);
                $agreementCustomize->original_provisions_id = $agreementOldVersion->agreement_provisions_id;
                $agreementCustomize->customize_provisions_id = 0;
                $agreementCustomize->original_item_id = $id;
                $agreementCustomize->customize_item_id = 0;
            }
        } else {
            if ($provisionId > 0) {
                $agreementCustomize->original_provisions_id = $provisionId;
                $agreementCustomize->customize_provisions_id = 0;
            } else {
                $agreementCustomize->original_provisions_id = 0;
                $agreementCustomize->customize_provisions_id = str_replace("c", "", $provisionId);
            }
            $agreementCustomize->original_item_id = $id;
            $agreementCustomize->customize_item_id = 0;
        }

        $agreementCustomize = $this->agreementCustomizeRepository->save($agreementCustomize);
        if ($data['edit_kind'] === AgreementCustomize::ADD) {
            $agreementCustomize->version_no = $agreementCustomize->version_no + 1;
            $agreementCustomize->customize_item_id = $agreementCustomize->id;
            $agreementCustomize->update_date = Carbon::now()->toDateTimeString();
            $agreementCustomize->update_user_id = Auth::user()->id;
            $this->agreementCustomizeRepository->save($agreementCustomize);

            return Lang::get('agreement_admin.registration_complete');
        }

        return Lang::get('agreement_admin.content_update_successfully');
    }

    /**
     * @param $data
     */
    public function deleteAgreementCustomizeProvisionWithCorp($data)
    {
        $customizeFlag = $data['customize_flag'];
        $id = $data['id'];
        $id = str_replace("c", "-", $id);
        if ($customizeFlag == "true") {
            if ($id < 0) {
                $agreementCustomize = $this->agreementCustomizeRepository->findLastestCustomize(0 - $id, 'customize_provisions_id', AgreementCustomize::AGREEMENT_PROVISIONS);
            } else {
                $agreementCustomize = $this->agreementCustomizeRepository->findLastestCustomize($id, 'original_provisions_id', AgreementCustomize::AGREEMENT_PROVISIONS);
            }
            $agreementCustomize->edit_kind = AgreementCustomize::DELETE;
            unset($agreementCustomize['id']);
        } else {
            $agreementCustomize = $this->getCustomizeObject(['corp_id'=>$data['corp_id']], $id);
            $agreementCustomize->edit_kind = AgreementCustomize::DELETE;
            $agreementCustomize->table_kind = AgreementCustomize::AGREEMENT_PROVISIONS;
            $agreementCustomize->original_provisions_id = $id;
            $agreementCustomize->customize_provisions_id = 0;
            $agreementCustomize->original_item_id = 0;
            $agreementCustomize->customize_item_id = 0;
            $agreementCustomize->content = $data['content'];
            $agreementCustomize->sort_no = $data['sort_no'];
        }
        $agreementCustomize = $agreementCustomize->toArray();
        $this->agreementCustomizeRepository->saveAgreementCustomize($agreementCustomize);
    }

    /**
     * @param $data
     */
    public function deleteAgreementCustomizeItemWithCorp($data)
    {
        $customizeFlag = $data['customize_flag'];
        $id = $data['id'];
        $id = str_replace("c", "-", $id);
        if ($customizeFlag == "true") {
            if ($id < 0) {
                $agreementCustomize = $this->agreementCustomizeRepository->findLastestCustomize(0 - $id, 'customize_item_id', AgreementCustomize::AGREEMENT_PROVISIONS_ITEM);
            } else {
                $agreementCustomize = $this->agreementCustomizeRepository->findLastestCustomize($id, 'original_item_id', AgreementCustomize::AGREEMENT_PROVISIONS_ITEM);
            }
            $agreementCustomize->edit_kind = AgreementCustomize::DELETE;
            unset($agreementCustomize['id']);
        } else {
            $agreementCustomize = $this->getCustomizeObject(['corp_id'=>$data['corp_id']], $id);
            $agreementCustomize->edit_kind = AgreementCustomize::DELETE;
            $agreementCustomize->table_kind = AgreementCustomize::AGREEMENT_PROVISIONS_ITEM;
            $agreementCustomize->original_provisions_id = $data['provision_id'];
            $agreementCustomize->customize_provisions_id = 0;
            $agreementCustomize->original_item_id = $id;
            $agreementCustomize->customize_item_id = 0;
            $agreementCustomize->content = $data['content'];
            $agreementCustomize->sort_no = $data['sort_no'];
        }
        $agreementCustomize = $agreementCustomize->toArray();
        $this->agreementCustomizeRepository->saveAgreementCustomize($agreementCustomize);
    }

    /**
     * @param $data
     * @param $id
     * @return AgreementCustomize
     */
    private function getCustomizeObject($data, $id)
    {
        $agreementCustomize = new AgreementCustomize();
        $agreementCustomize->fill($data);
        $agreementCustomize->agreement_id = 1;
        $corpAgreementList = $this->corpAgreementRepository->getAllByCorpId($data['corp_id'], 'asc');
        if (! checkIsNullOrEmptyCollection($corpAgreementList)) {
            $agreementCustomize->corp_agreement_id = $corpAgreementList->first()->id;
        }
        $agreementCustomize->last_history_id = $this->agreementRepository->findById(1)->last_history_id;
        $agreementCustomize->version_no = 1;
        $agreementCustomize->create_date = Carbon::now()->toDateTimeString();
        $agreementCustomize->create_user_id = Auth::user()->id;
        $agreementCustomize->update_date = Carbon::now()->toDateTimeString();
        $agreementCustomize->update_user_id = Auth::user()->id;
        if ($id > 0) {
            $agreementCustomize->original_id = $id;
        } else {
            $agreementCustomize->original_id = 0;
        }

        return $agreementCustomize;
    }
}
