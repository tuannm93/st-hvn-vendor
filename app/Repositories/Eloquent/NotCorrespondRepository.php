<?php

namespace App\Repositories\Eloquent;

use App\Models\MCorpTargetArea;
use App\Models\NotCorrespond;
use App\Models\NotCorrespondItem;
use App\Models\NearPreFectures;
use App\Repositories\NotCorrespondRepositoryInterface;
use DB;

class NotCorrespondRepository extends SingleKeyModelRepository implements NotCorrespondRepositoryInterface
{
    /**
     * @var NotCorrespond
     */
    protected $model;
    /**
     * @var NotCorrespondItem
     */
    protected $notCorrespondItem;

    /**
     * NotCorrespondRepository constructor.
     *
     * @param NotCorrespond     $model
     * @param NotCorrespondItem $notCorrespondItem
     */
    public function __construct(NotCorrespond $model, NotCorrespondItem $notCorrespondItem)
    {
        $this->model = $model;
        $this->notCorrespondItem = $notCorrespondItem;
    }

    /**
     * @return NotCorrespond|\App\Models\Base|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new NotCorrespond();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model|mixed|null|object|static
     */
    public function getFirstItem()
    {
        return $this->notCorrespondItem->orderBy('id', 'DESC')->first();
    }

    /**
     * @param integer $corpId
     * @return array|mixed
     */
    public function findNotCorrespond($corpId)
    {
        $items = $this->getFirstItem()->toArray();
        $prefectures = $this->getNearPrefecture($corpId);
        $fields ='not_corresponds.id, ' . 'not_corresponds.prefecture_cd, '
            . 'not_corresponds.jis_cd,' . '"MPost"."address1",' . '"MPost"."address2",' . 'not_corresponds.genre_id,' .
            '"MGenre".genre_name,' . '"MGenre".development_group,' .
            'not_corresponds.not_correspond_count_year ,' .
            'not_corresponds.not_correspond_count_latest,' .
            'min("NotCorrespondLog".import_date) as "NotCorrespond__min_import_date" ,' .
            'count("MCorpCategory".*) as "NotCorrespond__target_category" ,' .
            'count("MTargetArea".*) as "NotCorrespond__target_area",' .
            'count("MCorpTargetArea".*) as "NotCorrespond__target_corp_area"';

        $result = $this->model
            ->join(
                'm_genres AS MGenre',
                function ($join) {
                    $join->on('MGenre.id', '=', 'not_corresponds.genre_id');
                    $join->where('MGenre.valid_flg', '=', 1);
                }
            )
            ->leftJoin(
                'm_corp_categories AS MCorpCategory',
                function ($join) use ($corpId) {
                    $join->on('MCorpCategory.genre_id', '=', 'not_corresponds.genre_id');
                    $join->where('MCorpCategory.corp_id', '=', $corpId);
                }
            )
            ->leftJoin(
                'm_target_areas AS MTargetArea',
                function ($join) {
                    $join->on('MTargetArea.corp_category_id', '=', 'MCorpCategory.id');
                    $join->on('MTargetArea.jis_cd', '=', 'not_corresponds.jis_cd');
                }
            )
            ->leftJoin(
                'm_corp_target_areas AS MCorpTargetArea',
                function ($join) use ($corpId) {
                    $join->on('MCorpTargetArea.jis_cd', '=', 'not_corresponds.jis_cd');
                    $join->where('MCorpTargetArea.corp_id', '=', $corpId);
                }
            )
            ->join(
                DB::raw('(SELECT jis_cd, address1, address2 FROM m_posts AS MPostA GROUP BY jis_cd, address1, address2) as "MPost"'),
                function ($join) {
                    $join->on('MPost.jis_cd', '=', 'not_corresponds.jis_cd');
                }
            )
            ->join(
                'not_correspond_logs AS NotCorrespondLog',
                function ($join) use ($items) {
                    $join->on('NotCorrespondLog.not_correspond_id', '=', 'not_corresponds.id');
                    $join->where(
                        function ($query) use ($items) {
                            $query->orWhere('NotCorrespondLog.not_correspond_count_year', '>=', $items['small_lower_limit']);
                            $query->orWhere('NotCorrespondLog.not_correspond_count_latest', '>=', $items['immediate_lower_limit']);
                        }
                    );
                }
            )
            ->where(
                function ($query) use ($items) {
                    $query->orWhere('not_corresponds.not_correspond_count_year', '>=', $items['small_lower_limit']);
                    $query->orWhere('not_corresponds.not_correspond_count_latest', '>=', $items['immediate_lower_limit']);
                }
            )
            ->whereIn('not_corresponds.prefecture_cd', $prefectures)
            ->groupBy(
                'not_corresponds.id',
                'not_corresponds.jis_cd',
                'not_corresponds.prefecture_cd',
                'not_corresponds.genre_id',
                'MPost.address1',
                'MPost.address2',
                'MGenre.genre_name',
                'MGenre.development_group',
                'not_corresponds.not_correspond_count_year',
                'not_corresponds.not_correspond_count_latest'
            )
            ->havingRaw('COUNT("MTargetArea".id) < 1 ')
            ->orderBy('MGenre.development_group', 'ASC')
            ->orderBy('not_corresponds.genre_id', 'ASC')
            ->orderBy('not_corresponds.jis_cd', 'ASC')
            ->orderBy('not_corresponds.not_correspond_count_latest', 'ASC')
            ->orderBy('not_corresponds.not_correspond_count_year', 'ASC')
            ->selectRaw($fields)->get()->toArray();

        return $result;
    }

    /**
     * @param integer $corpId
     * @return array
     */
    private function getCorpPrefecture($corpId)
    {
        $corpPrefecture = new MCorpTargetArea();
        $prefectures = [];
        $dataPrefecture = $corpPrefecture->select(DB::raw('distinct substring(jis_cd, 1, 2) as "MCorpTargetArea__prefecture_cd"'))
            ->where('corp_id', $corpId)->get()->toArray();
        foreach ($dataPrefecture as $val) {
            $prefectures[] = (int)$val['MCorpTargetArea__prefecture_cd'];
        }

        return $prefectures;
    }

    /**
     * @param integer $corpId
     * @return array
     */
    private function getNearPrefecture($corpId)
    {
        $nearPrefucture = new NearPreFectures();
        $prefectures = $this->getCorpPrefecture($corpId);
        $dataNearPrecfure = $nearPrefucture->select('near_prefecture_cd')->whereIn('prefecture_cd', $prefectures)->get()->toArray();
        foreach ($dataNearPrecfure as $val) {
            if (!array_search($val['near_prefecture_cd'], $prefectures)) {
                $prefectures[] = (int)$val['near_prefecture_cd'];
            }
        }

        return $prefectures;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function updateById($data)
    {
        $id = $data['id'];
        unset($data['id']);
        return $this->model->where('id', $id)->update($data);
    }

    /**
     * @param array $ids
     * @return bool|mixed|null
     * @throws \Exception
     */
    public function deleteMultiRecord($ids)
    {
        return $this->model->whereIn('id', $ids)->delete();
    }
}
