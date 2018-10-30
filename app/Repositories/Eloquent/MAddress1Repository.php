<?php


namespace App\Repositories\Eloquent;

use App\Models\MAddress1;
use App\Repositories\MAddress1RepositoryInterface;
use Illuminate\Support\Facades\DB;

class MAddress1Repository extends SingleKeyModelRepository implements MAddress1RepositoryInterface
{

    /**
     * @var MAddress1
     */
    protected $model;


    /**
     * MAddress1Repository constructor.
     *
     * @param MAddress1 $model
     */
    public function __construct(MAddress1 $model)
    {
        $this->model = $model;
    }

    /**
     * @param string $address1
     * @return \Illuminate\Database\Eloquent\Model|mixed|null|object|static
     */
    public function findByAddressName($address1)
    {
        return $this->model->where('address1', $address1)->first();
    }

    /**
     * @param integer $corpId
     * @return \Illuminate\Support\Collection|mixed
     */
    public function findByCorpIdAndPrefecturalCode($corpId)
    {
        $mCorpTargetAreaQuery = DB::table('m_corp_target_areas')->where('corp_id', $corpId);

        $addressCodeQuery = DB::table(DB::raw('(' . $mCorpTargetAreaQuery->toSql() . ') as mta'))
            ->selectRaw('DISTINCT substr(mta.jis_cd, 1, 2) AS "addressCode"')
            ->mergeBindings($mCorpTargetAreaQuery);

        $mAddress1List = DB::table(DB::raw('(' . $addressCodeQuery->toSql() . ') AS cp'))
            ->select('a.*')
            ->join('m_address1 AS a', 'address1_cd', '=', 'cp.addressCode')
            ->mergeBindings($addressCodeQuery);
        return $mAddress1List->get();
    }

    /**
     * @param integer $corpId
     * @return array|mixed
     */
    public function findAreaDataSetByCorpIdAndPrefecturalCode($corpId)
    {
        $result = $this->model->select(
            'tmp2.jis_cd as address1_cd',
            'm_address1.address1 as address1',
            'tmp2.jis_count as data_post_count',
            'tmp3.registered_count as register_post_count'
        )
            ->join(
                DB::raw('(select jis_cd ,count(*) as  jis_count from
                ( select substr(mp.jis_cd, 1, 2) as jis_cd from m_posts as mp group by mp.jis_cd, mp.address2 )
                as tmp1 group by jis_cd ) as tmp2'),
                'tmp2.jis_cd',
                '=',
                'm_address1.address1_cd'
            )
            ->leftjoin(
                DB::raw('(select jis_cd_2, count(jis_cd_2) as registered_count
                from (select jis_cd, substr(jis_cd, 1, 2) as jis_cd_2 from m_corp_target_areas where corp_id = :corpId ) as mta
               group by jis_cd_2 ) as tmp3'),
                'tmp3.jis_cd_2',
                '=',
                'm_address1.address1_cd'
            )->addBinding(['corpId' => $corpId])
            ->orderBy('m_address1.address1_cd')->get();

        return $result;
    }

    /**
     * @return array|mixed
     */
    public function getList()
    {
        return $this->model->orderBy('address1_cd', 'ASC')
            ->pluck('address1', 'address1_cd')->toarray();
    }

    /**
     * @param $addressCd
     * @return \Illuminate\Database\Eloquent\Model|mixed|null|object|static
     */
    public function findByAddressCd($addressCd)
    {
        return $this->model->where('address1_cd', $addressCd)->first();
    }
}
