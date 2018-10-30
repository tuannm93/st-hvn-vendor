<?php

namespace App\Services\Report;

use App\Repositories\MCorpRepositoryInterface;
use Yajra\DataTables\DataTables;

class ReportDevSearchService
{
    /**
     * @var MCorpRepositoryInterface
     */
    protected $mCorpRepository;

    /**
     * ReportDevSearchService constructor.
     *
     * @param MCorpRepositoryInterface $mCorpRepository
     */
    public function __construct(
        MCorpRepositoryInterface $mCorpRepository
    ) {
        $this->mCorpRepository = $mCorpRepository;
    }

    /**
     * @param integer $genreId
     * @return array
     */
    public function getMCropUnattended($genreId)
    {
        $noAttackList = [];
        $lists = $this->mCorpRepository->getUnattendedForReportDevByGenreId($genreId);
        foreach ($lists as $item) {
            $noAttackList[$item->address1] = $item->total;
        }

        return $noAttackList;
    }

    /**
     * @param integer $genreId
     * @return array
     */
    public function getMCropAdvance($genreId)
    {
        $advanceList = [];
        $lists = $this->mCorpRepository->getAdvanceForReportDevByGenreId($genreId);
        foreach ($lists as $item) {
            $advanceList[$item->address1] = $item->total;
        }

        return $advanceList;
    }

    /**
     * @param integer $genreId
     * @param string $address
     * @param string $status
     * @return mixed
     * @throws \Exception
     */
    public function getListForDataTableByGenreIdAndAddressAndStatus($genreId, $address, $status)
    {
        $prefecture = getDivList('rits.prefecture_div', 'rits_config');
        $query = $this->mCorpRepository->getListForDataTableByGenreIdAndAddressAndStatus($genreId, $address, $status);
        $datatableQuery = DataTables::of($query)
            ->addColumn('prefecture', function () use ($prefecture, $address) {
                return $prefecture[$address];
            })->addColumn('official_corp_link', function ($query) {
                return route('affiliation.detail.edit', $query->id);
            });

        return $datatableQuery->make(true);
    }
}
