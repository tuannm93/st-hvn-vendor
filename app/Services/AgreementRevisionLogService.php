<?php


namespace App\Services;

use Yajra\DataTables\DataTables;
use App\Repositories\AgreementRevisionLogRepositoryInterface;
use Illuminate\Http\Request;

class AgreementRevisionLogService
{

    /**
     * @var AgreementRevisionLogRepositoryInterface
     */
    protected $agreementRevisionLogRepository;

    /**
     * AgreementRevisionLogService constructor.
     *
     * @param AgreementRevisionLogRepositoryInterface $agreementRevisionLogRepository
     */
    public function __construct(
        AgreementRevisionLogRepositoryInterface $agreementRevisionLogRepository
    ) {
        $this->agreementRevisionLogRepository = $agreementRevisionLogRepository;
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function getContractTermsRevisionHistoryData(Request $request)
    {
        // using having for search data instead of where condition

        $query = $this->agreementRevisionLogRepository->getAllContractTermsRevisionHistoryJoinMUserWithoutContent();

        // column search
        if (!validateStringIsNullOrEmpty($request->input('columns.1.search.value'))) {
            $columnSearchValue = $request->input('columns.1.search.value');
            $query = $query->havingRaw("LOWER(to_char(arl.created, 'YYYY/MM/DD HH24:MI')) like LOWER(?)", ["%{$columnSearchValue}%"]);
        }
        if (!validateStringIsNullOrEmpty($request->input('columns.2.search.value'))) {
            $columnSearchValue = $request->input('columns.2.search.value');
            $query = $query->havingRaw("LOWER(u.user_name) like LOWER(?)", ["%{$columnSearchValue}%"]);
        }

        $datatableQuery = DataTables::of($query)
            ->filterColumn(
                'created_date',
                function ($query, $keyword) {
                }
            )
            ->filterColumn(
                'u.user_name',
                function ($query, $keyword) {
                }
            );

        $datatableQuery = $datatableQuery
            ->addColumn(
                'detail_url',
                function ($query) {
                    return action(
                        'Agreement\AgreementProvisionsController@getContractTermsRevisionHistoryDetail',
                        ['id' => $query->id]
                    );
                }
            );

        return $datatableQuery->make(true);
    }
}
