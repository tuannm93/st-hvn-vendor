<?php

namespace App\Repositories;

interface AuctionRankingRepoInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param string $endDate
     * @param string $startDate
     * @return mixed
     */
    public function getData($endDate, $startDate);

    /**
     * @param string $endDate
     * @param string $startDate
     * @return mixed
     */
    public function getDataPaginateAuctionRanking($endDate, $startDate);

    /**
     * @param string $endDate
     * @param string $startDate
     * @return mixed
     */
    public function getDataCSVAuctionRanking($endDate, $startDate);

    /**
     * @param string $endDate
     * @param string $startDate
     * @return mixed
     */
    public function getCountAuctionRanking($endDate, $startDate);
}
