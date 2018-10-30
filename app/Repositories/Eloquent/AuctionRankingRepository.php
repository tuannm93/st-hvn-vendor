<?php

namespace App\Repositories\Eloquent;

use App\Models\CommissionInfo;
use App\Repositories\AuctionRankingRepoInterface;
use Illuminate\Support\Facades\DB;

class AuctionRankingRepository extends SingleKeyModelRepository implements AuctionRankingRepoInterface
{
    /**
     * @var CommissionInfo
     */
    protected $commissionInfo;

    /**
     * AuctionRankingRepository constructor.
     *
     * @param CommissionInfo $commissionInfo
     */
    public function __construct(CommissionInfo $commissionInfo)
    {
        $this->commissionInfo = $commissionInfo;
    }

    /**
     * @param string $endDate
     * @param string $startDate
     * @return $this
     */
    public function getData($endDate, $startDate)
    {
        $feilds = [
            'MCorp.official_corp_name as MCorp__official_corp_name',
            'commission_infos.corp_id as CommissionInfo_corp_id',
            DB::raw('Count(*) as "CommissionInfo__ranking"')

        ];

        $sectionType = array_flip(\Config::get('datacustom.selection_type'));
        $selectionSystem = [$sectionType['auction_selection'], $sectionType['automatic_auction_selection']];

        return $this->commissionInfo->select($feilds)
            ->join(
                'm_corps AS MCorp',
                function ($join) {
                    $join->on('MCorp.id', '=', 'commission_infos.corp_id');
                    $join->where('MCorp.del_flg', 0);
                }
            )
            ->join(
                'demand_infos AS DemandInfo',
                function ($join) {
                    $join->on('DemandInfo.id', '=', 'commission_infos.demand_id');
                }
            )
            ->where(
                function ($query) use ($selectionSystem, $endDate, $startDate) {
                    $query->whereIn('DemandInfo.selection_system', $selectionSystem);
                    $query->where('commission_infos.commit_flg', 1);
                    $query->whereRaw("to_char(commission_infos.created, 'yyyy-mm-dd') <= ? ", $endDate);
                    $query->whereRaw("to_char(commission_infos.created, 'yyyy-mm-dd') >= ?", $startDate);
                }
            )
            ->groupBy('commission_infos.corp_id', 'MCorp.official_corp_name');
    }

    /**
     * @param $endDate
     * @param $startDate
     * @return mixed
     */
    public function getDataPaginateAuctionRanking($endDate, $startDate)
    {
        $query = $this->getData($endDate, $startDate);
        return $query->simplePaginate(100);
    }

    /**
     * @param string $endDate
     * @param  string $startDate
     * @return mixed
     */
    public function getDataCSVAuctionRanking($endDate, $startDate)
    {
        $query = $this->getData($endDate, $startDate)->orderBy('CommissionInfo__ranking', 'desc');
        return $query->get()->toArray();
    }

    /**
     * @param string $endDate
     * @param string $startDate
     * @return mixed
     */
    public function getCountAuctionRanking($endDate, $startDate)
    {
        $query = $this->getData($endDate, $startDate);
        return $query->get()->count();
    }
}
