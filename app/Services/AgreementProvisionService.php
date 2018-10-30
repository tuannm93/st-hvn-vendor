<?php


namespace App\Services;

use App\Models\AgreementProvision;
use App\Models\AgreementProvisionItem;
use App\Models\AgreementProvisionsEditLog;
use App\Repositories\AgreementProvisionsEditLogRepositoryInterface;
use App\Repositories\AgreementProvisionsItemRepositoryInterface;
use App\Repositories\AgreementProvisionsRepositoryInterface;
use App\Repositories\AgreementRepositoryInterface;
use App\Repositories\AgreementRevisionLogRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class AgreementProvisionService
{

    /**
     * @var AgreementProvisionsRepositoryInterface
     */
    protected $agreementProvisionsRepository;
    /**
     * @var AgreementProvisionsEditLogRepositoryInterface
     */
    protected $agreementProEditLogRepo;
    /**
     * @var AgreementRepositoryInterface
     */
    protected $agreementRepository;
    /**
     * @var AgreementRevisionLogRepositoryInterface
     */
    protected $agreementRevisionLogRepository;
    /**
     * @var AgreementProvisionsItemRepositoryInterface
     */
    protected $agreementProItemRepo;

    /**
     * AgreementProvisionService constructor.
     * @param AgreementProvisionsRepositoryInterface $agreementProvisionsRepository
     * @param AgreementProvisionsEditLogRepositoryInterface $agreementProEditLogRepo
     * @param AgreementRepositoryInterface $agreementRepository
     * @param AgreementRevisionLogRepositoryInterface $agreementRevisionLogRepository
     * @param AgreementProvisionsItemRepositoryInterface $agreementProItemRepo
     */
    public function __construct(
        AgreementProvisionsRepositoryInterface $agreementProvisionsRepository,
        AgreementProvisionsEditLogRepositoryInterface $agreementProEditLogRepo,
        AgreementRepositoryInterface $agreementRepository,
        AgreementRevisionLogRepositoryInterface $agreementRevisionLogRepository,
        AgreementProvisionsItemRepositoryInterface $agreementProItemRepo
    ) {
        $this->agreementProvisionsRepository = $agreementProvisionsRepository;
        $this->agreementProEditLogRepo = $agreementProEditLogRepo;
        $this->agreementRepository = $agreementRepository;
        $this->agreementRevisionLogRepository = $agreementRevisionLogRepository;
        $this->agreementProItemRepo = $agreementProItemRepo;
    }

    /**
     * @return null
     */
    public function getAgreementProvisionAndItemData()
    {
        $agreementProvisions = null;
        if ($this->agreementProvisionsRepository->findAgreementById(1, false) != 0) {
            $agreementProvisions = $this->agreementProvisionsRepository->findAgreementProvisionsByAgreementId(1, false);
            $agreementProvisions->load(
                [
                'agreementProvisionItem' => function ($query) {
                    $query->where('delete_flag', false);
                    $query->orderBy('sort_no');
                }
                ]
            );
        }
        return $agreementProvisions;
    }

    /**
     * @return null
     */
    public function getAgreementProvisionData()
    {
        $agreementProvisions = null;
        if ($this->agreementProvisionsRepository->findAgreementById(1, false) != 0) {
            $agreementProvisions = $this->agreementProvisionsRepository->findAgreementProvisionsByAgreementId(1, false);
        }
        return $agreementProvisions;
    }

    /**
     * @param $dataProvision
     * @param $agreement
     * @return array|null|string
     */
    public function saveAgreementProvision($dataProvision, $agreement)
    {
        $message = Lang::get('agreement_admin.registration_complete');
        $agreementProvision = new AgreementProvision();
        $agreementProvision->fill($dataProvision);
        if ($agreementProvision->id != null) {
            // update event
            $message = Lang::get('agreement_admin.content_update_successfully');
            $agreementProvision = $this->agreementProvisionsRepository->findById($agreementProvision->id);
            // check have change
            if ($agreementProvision->sort_no != $dataProvision['sort_no']
                || $agreementProvision->provisions != $dataProvision['provisions']
            ) {
                $agreementProvision->fill($dataProvision);
                $agreementProvision->version_no = $agreementProvision->version_no + 1;
                $agreementProvision->update_date = Carbon::now()->toDateTimeString();
                $agreementProvision->update_user_id = Auth::user()->id;
            }
        } else {
            // insert event
            $agreementProvision->last_history_id = 0;
            $agreementProvision->create_date = Carbon::now()->toDateTimeString();
            $agreementProvision->create_user_id = Auth::user()->id;
            $agreementProvision->update_date = Carbon::now()->toDateTimeString();
            $agreementProvision->update_user_id = Auth::user()->id;
            $agreementProvision->version_no = 1;
        }
        $agreementProvision->agreement_id = $agreement->id;

        $agreementProvision = $this->agreementProvisionsRepository->save($agreementProvision);
        $this->insertAgreementProvisionsEditLog($agreementProvision);

        return $message;
    }

    /**
     * @param $dataProvisionItem
     * @return array|null|string
     */
    public function saveAgreementProvisionItem($dataProvisionItem)
    {
        $message = Lang::get('agreement_admin.registration_complete');
        $agreementProvisionItem = new AgreementProvisionItem();
        $agreementProvisionItem->fill($dataProvisionItem);

        if ($agreementProvisionItem->id != null) {
            // update event
            $message = Lang::get('agreement_admin.content_update_successfully');
            $agreementProvisionItem = $this->agreementProItemRepo->findById($agreementProvisionItem->id);
            // check have change
            if ($agreementProvisionItem->sort_no != $dataProvisionItem['sort_no']
                || $agreementProvisionItem->item != $dataProvisionItem['item']
            ) {
                $agreementProvisionItem->fill($dataProvisionItem);
                $agreementProvisionItem->version_no = $agreementProvisionItem->version_no + 1;
                $agreementProvisionItem->update_date = Carbon::now()->toDateTimeString();
                $agreementProvisionItem->update_user_id = Auth::user()->id;
            }
        } else {
            // insert event
            $agreementProvisionItem->version_no = 1;
            $agreementProvisionItem->last_history_id = 0;
            $agreementProvisionItem->create_date = Carbon::now()->toDateTimeString();
            $agreementProvisionItem->create_user_id = Auth::user()->id;
            $agreementProvisionItem->update_date = Carbon::now()->toDateTimeString();
            $agreementProvisionItem->update_user_id = Auth::user()->id;
        }

        $agreementProvisionItem = $this->agreementProItemRepo->save($agreementProvisionItem);

        $provision = $this->agreementProvisionsRepository->findById($agreementProvisionItem->agreement_provisions_id);
        $this->insertAgreementProvisionsEditLog($provision);

        return $message;
    }

    /**
     * @param $agreementProvision
     * @return AgreementProvisionsEditLog
     */
    private function insertAgreementProvisionsEditLog($agreementProvision)
    {
        $agreementProvisionsEditLog = new AgreementProvisionsEditLog();
        $agreementProvisionsEditLog->agreement_provisions_id = $agreementProvision->id;
        $agreementProvisionsEditLog->content = $agreementProvision->getContentAndAllItems();
        $agreementProvisionsEditLog->created = Carbon::now()->toDateTimeString();
        $agreementProvisionsEditLog->modified = $agreementProvisionsEditLog->created;
        $agreementProvisionsEditLog->created_user_id = Auth::user()->id;
        $agreementProvisionsEditLog->modified_user_id = $agreementProvisionsEditLog->created_user_id;
        $agreementProvisionsEditLog->agreement_revision_logs_id = $this->agreementRevisionLogRepository->getMaxAgreementRevisionLogId();

        $agreementProvisionsEditLog = $this->agreementProEditLogRepo->save($agreementProvisionsEditLog);

        return $agreementProvisionsEditLog;
    }

    /**
     * @param $dataList
     */
    public function versionUp($dataList)
    {
        $content = "";
        foreach ($dataList as $data) {
            $content = $content . $data->getContentAndAllItems() . "\n";
        }

        $agreementRevisionLogData = [
            'content' => $content,
            'modified' => date('Y-m-d H:i:s'),
            'created' => date('Y-m-d H:i:s'),
            'modified_user_id' => Auth::user()->id,
            'created_user_id' => Auth::user()->id,
        ];
        $this->agreementRevisionLogRepository->insert($agreementRevisionLogData);

        $agreementId = 1;
        $agreement = $this->agreementRepository->findById($agreementId);
        $newLastHistoryId = $agreement->last_history_id + 1;
        $agreementUpdateData = [
            'ticket_no' => $agreement->ticket_no + 1,
            'last_history_id' => $newLastHistoryId,
            'version_no' => $agreement->version_no + 1,
            'update_date' => Carbon::now()->toDateTimeString(),
            'update_user_id' => Auth::user()->id,
        ];
        $this->agreementRepository->update($agreementId, $agreementUpdateData);

        // update last history id
        $agreementProvisions = null;
        if ($this->agreementProvisionsRepository->findAgreementById(1, false) != 0) {
            $agreementProvisions = $this->agreementProvisionsRepository->findAgreementProvisionsByAgreementId(1, false);
            $agreementProvisions->load(
                [
                'agreementProvisionItem' => function ($query) {
                    $query->where('delete_flag', false);
                    $query->orderBy('sort_no');
                }
                ]
            );
        }
        foreach ($agreementProvisions as $agreementProvision) {
            if (! checkIsNullOrEmptyCollection($agreementProvision->agreementProvisionItem)) {
                $agreementProvision->last_history_id = $newLastHistoryId;
                $agreementProvision->version_no = $agreementProvision->version_no + 1;
                $agreementProvision->update_user_id = Auth::user()->id;
                $agreementProvision->update_date = Carbon::now()->toDateTimeString();
            }

            $this->agreementProvisionsRepository->save($agreementProvision);
        }
    }

    /**
     * @param $id
     */
    public function deleteProvision($id)
    {
        $this->agreementProItemRepo->deleteByColumn('agreement_provisions_id', $id);
        $this->agreementProvisionsRepository->deleteById($id);
    }

    /**
     * @param $id
     */
    public function deleteItem($id)
    {
        // get provision id
        $agreementProvisionItem = $this->agreementProItemRepo->findById($id);
        $agreementProvisionId = $agreementProvisionItem->agreement_provisions_id;

        // delete item
        $this->agreementProItemRepo->deleteById($id);

        // write log
        $provision = $this->agreementProvisionsRepository->findById($agreementProvisionId);
        $this->insertAgreementProvisionsEditLog($provision);
    }
}
