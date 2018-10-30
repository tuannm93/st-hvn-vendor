<?php

namespace App\Repositories\Eloquent;

use App\Models\MPost;
use App\Repositories\MPostRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MPostRepository extends SingleKeyModelRepository implements MPostRepositoryInterface
{
    /**
     * @var MPost
     */
    protected $model;

    /**
     * MPostRepository constructor.
     *
     * @param MPost $model
     */
    public function __construct(MPost $model)
    {
        $this->model = $model;
    }

    /**
     * @return \App\Models\Base|MPost|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new MPost();
    }

    /**
     * get corp pref area count function
     *
     * @param  integer $id
     * @param  string  $address1
     * @return array
     */
    public function getCorpPrefAreaCount($id = null, $address1 = null)
    {
        $query = $this->model
            ->select(DB::raw('COUNT (m_posts.jis_cd) AS count'))
            ->rightJoin(
                'm_corp_target_areas',
                function ($join) use ($id) {
                    /**
                * @var JoinClause $join
                */
                    $join->on('m_corp_target_areas.jis_cd', '=', 'm_posts.jis_cd')
                        ->where('m_corp_target_areas.corp_id', '=', $id);
                }
            )
            ->where('m_posts.address1', '=', $address1)
            ->groupBy('m_posts.jis_cd', 'm_posts.address2', 'm_corp_target_areas.corp_id')
            ->orderBy('m_posts.jis_cd', 'asc');

        return $query->get()->count();
    }

    /**
     * @param integer $corpId
     * @param string $address
     * @return int|mixed
     */
    public function getCorpCategoryAreaCount($corpId = null, $address = null)
    {
        $query = $this->model
            ->select(DB::raw('COUNT (m_posts.jis_cd) AS count'))
            ->rightJoin(
                'm_target_areas',
                function ($join) use ($corpId) {
                    /**
                * @var JoinClause $join
                */
                    $join->on('m_target_areas.jis_cd', '=', 'm_posts.jis_cd')
                        ->where('m_target_areas.corp_category_id', '=', $corpId);
                }
            )
            ->where('m_posts.address1', '=', $address)
            ->groupBy('m_posts.jis_cd', 'm_posts.address2', 'm_target_areas.id')
            ->orderBy('m_posts.jis_cd', 'asc');

        return $query->get()->count();
    }

    /**
     * @param integer $corpId
     * @param string $address1
     * @return array|mixed
     */
    public function searchTargetArea($corpId, $address1)
    {
        return $this->model->select('m_posts.address2', 'm_posts.jis_cd', DB::raw('max(m_target_areas.id) as "m_target_area_id"'))
            ->leftJoin(
                'm_target_areas',
                function ($join) use ($corpId) {
                    /**
                * @var JoinClause $join
                */
                    $join->on('m_target_areas.jis_cd', '=', 'm_posts.jis_cd')
                        ->where('m_target_areas.corp_category_id', '=', $corpId);
                }
            )
            ->where('m_posts.address1', '=', $address1)
            ->groupBy('m_posts.jis_cd', 'm_posts.address2')
            ->orderBy('m_posts.jis_cd', 'asc')->get()->toarray();
    }

    /**
     * @param integer $corpId
     * @param array $dataRequest
     * @return bool|mixed
     */
    public function registTargetArea($corpId, $dataRequest)
    {
        $conditionsResults = $this->model->select(
            \DB::raw("MAX(m_posts.jis_cd) AS max_jis"),
            \DB::raw("MIN(m_posts.jis_cd) AS min_jis")
        )
            ->where('m_posts.address1', '=', $dataRequest['address1_text'])->first()->toarray();
        if (empty($conditionsResults) || empty($corpId)) {
            return false;
        }
        DB::table('m_target_areas')->where('corp_category_id', '=', $corpId)
            ->where('jis_cd', '>=', $conditionsResults['min_jis'])
            ->where('jis_cd', '<=', $conditionsResults['max_jis'])
            ->delete();
        if (!empty($dataRequest['data']['jis_cd'])) {
            $saveData = [];
            foreach ($dataRequest['data']['jis_cd'] as $val) {
                $setData = [];
                $setData['corp_category_id'] = $corpId;
                $setData['jis_cd'] = $val;
                $setData['address1_cd'] = substr($val, 0, 2);
                $setData['created_user_id']  = Auth::user()['user_id'];
                $setData['created']  = Carbon::now();
                $setData['modified']  = Carbon::now();
                $setData['modified_user_id']  = Auth::user()['user_id'];
                $saveData[] = $setData;
            }
            if (!empty($saveData)) {
                try {
                    DB::beginTransaction();
                    DB::table('m_target_areas')->insert($saveData);
                    DB::commit();
                    return true;
                } catch (\Exception $e) {
                    DB::rollBack();
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * get pref area count function
     *
     * @param  string $address1
     * @return array
     */
    public function getPrefAreaCount($address1 = null)
    {
        $query = $this->model
            ->select(DB::raw('COUNT (m_posts.jis_cd) AS count'))
            ->where('m_posts.address1', '=', $address1)
            ->groupBy('m_posts.jis_cd', 'm_posts.address2');

        return $query->get()->count();
    }

    /**
     * search corp target function
     *
     * @param  integer $id
     * @param  string  $address1
     * @return array
     */
    public function searchCorpTargetArea($id = null, $address1 = null)
    {
        $result = $this->model->select('m_posts.address2', 'm_posts.jis_cd', 'm_corp_target_areas.corp_id')
            ->leftjoin(
                'm_corp_target_areas',
                function ($join) use ($id) {
                    $join->on('m_corp_target_areas.jis_cd', '=', 'm_posts.jis_cd');
                    $join->on('m_corp_target_areas.corp_id', '=', DB::raw($id));
                }
            )
            ->where('m_posts.address1', '=', $address1)
            ->groupBy('m_posts.jis_cd', 'm_posts.address2', 'm_corp_target_areas.corp_id')
            ->orderBy('m_posts.jis_cd', 'asc')->get()->toarray();
        return $result;
    }

    /**
     * edit corp by id function
     *
     * @param  integer $id
     * @param  array   $data
     * @return boolean
     */
    public function editTargetArea2($id, $data)
    {
        $conditionsResults = $this->model->select(
            \DB::raw("MAX(m_posts.jis_cd) AS max_jis"),
            \DB::raw("MIN(m_posts.jis_cd) AS min_jis")
        )
            ->where('m_posts.address1', '=', $data['address1_text'])->first()->toarray();
        if (empty($conditionsResults) || empty($id)) {
            return false;
        }
        DB::table('m_corp_target_areas')->where('corp_id', '=', $id)
            ->where('jis_cd', '>=', $conditionsResults['min_jis'])
            ->where('jis_cd', '<=', $conditionsResults['max_jis'])
            ->delete();
        $userId = Auth::user()->user_id;
        $date = Carbon::now();
        if (!empty($data['data']['jis_cd'])) {
            foreach ($data['data']['jis_cd'] as $val) {
                $setData = [];
                $setData['corp_id'] = $id;
                $setData['jis_cd'] = $val;
                $setData['modified_user_id'] = $userId;
                $setData['modified'] = $date;
                $setData['created_user_id'] = $userId;
                $setData['created'] = $date;
                $saveData[] = $setData;
            }
            if (!empty($saveData)) {
                try {
                    DB::beginTransaction();
                    DB::table('m_corp_target_areas')->insert($saveData);
                    DB::commit();
                    return true;
                } catch (\Exception $e) {
                    DB::rollBack();
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * edit corp by id function
     *
     * @param  integer $id
     * @param  array   $data
     * @return boolean
     */
    public function editTargetArea($id, $data)
    {
        $conditionsResults = $this->model->select(
            \DB::raw("MAX(m_posts.jis_cd) AS max_jis"),
            \DB::raw("MIN(m_posts.jis_cd) AS min_jis")
        )
            ->where('m_posts.address1', '=', $data['address1_text'])->first()->toarray();
        if (empty($conditionsResults) || empty($id)) {
            return false;
        }
        if (!empty($conditionsResults['min_jis']) && !empty($conditionsResults['max_jis'])) {
            DB::table('m_corp_target_areas')->where('corp_id', '=', $id)
                ->where('jis_cd', '>=', $conditionsResults['min_jis'])
                ->where('jis_cd', '<=', $conditionsResults['max_jis'])
                ->delete();
        }
        if (!empty($data['jis_cd'])) {
            foreach ($data['jis_cd'] as $val) {
                $setData = [];
                $setData['corp_id'] = $id;
                $setData['jis_cd'] = $val;
                $saveData[] = $setData;
            }
            if (!empty($saveData)) {
                try {
                    DB::beginTransaction();
                    DB::table('m_corp_target_areas')->insert($saveData);
                    DB::commit();
                    return true;
                } catch (\Exception $e) {
                    DB::rollBack();
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * edit corp by id function
     * @param null $id
     * @param null $address1
     * @return bool|mixed
     */
    public function allRegistTargetArea($id = null, $address1 = null)
    {
        $registAlreadyData = DB::table('m_corp_target_areas')
            ->where('corp_id', '=', $id)->get()->toarray();
        $conditions = [];
        foreach ($registAlreadyData as $val) {
            array_push($conditions, ['m_posts.jis_cd', '!=', $val->jis_cd]);
        }
        $results = $this->model->select('m_posts.jis_cd')
            ->leftjoin(
                'm_corp_target_areas',
                function ($join) use ($id) {
                    $join->on('m_corp_target_areas.jis_cd', '=', 'm_posts.jis_cd');
                    $join->on('m_corp_target_areas.corp_id', '=', DB::raw($id));
                }
            )->where($conditions)
            ->groupBy('m_posts.jis_cd', 'm_posts.address2', 'm_corp_target_areas.corp_id')
            ->orderBy('m_posts.jis_cd', 'asc');
        if (!empty($address1)) {
            $results = $results->where('m_posts.address1', $address1)
                ->get()->toarray();
        } else {
            $results = $results->get()->toarray();
        }
        foreach ($results as $v) {
            $data['corp_id'] = $id;
            $data['jis_cd'] = $v['jis_cd'];
            $saveData[] = $data;
        }
        if (!empty($saveData)) {
            try {
                DB::beginTransaction();
                DB::table('m_corp_target_areas')->insert($saveData);
                DB::commit();
                return true;
            } catch (\Exception $e) {
                DB::rollBack();
                return false;
            }
        }
        return true;
    }

    /**
     * edit corp by id function
     *
     * @param  integer $corpId
     * @param  array   $address
     * @return boolean
     */
    public function registTargetAreaAddress($corpId = null, $address = null)
    {
        $registAlreadyData = DB::table('m_corp_target_areas')
            ->where('corp_id', '=', $corpId)->get()->toarray();
        $conditions = [];
        foreach ($registAlreadyData as $val) {
            array_push($conditions, ['m_posts.jis_cd', '!=', $val->jis_cd]);
        }
        $results = $this->model->select('m_posts.jis_cd')
            ->leftjoin(
                'm_corp_target_areas',
                function ($join) use ($corpId) {
                    $join->on('m_corp_target_areas.jis_cd', '=', 'm_posts.jis_cd');
                    $join->on('m_corp_target_areas.corp_id', '=', DB::raw($corpId));
                }
            )->where($conditions)
            ->whereIn('m_posts.address1', $address)
            ->groupBy('m_posts.jis_cd', 'm_posts.address2', 'm_corp_target_areas.corp_id')
            ->orderBy('m_posts.jis_cd', 'asc')->get()->toarray();
        foreach ($results as $v) {
            $data['corp_id'] = $corpId;
            $data['jis_cd'] = $v['jis_cd'];
            $saveData[] = $data;
        }
        if (!empty($saveData)) {
            try {
                DB::beginTransaction();
                DB::table('m_corp_target_areas')->insert($saveData);
                DB::commit();
                return true;
            } catch (\Exception $e) {
                DB::rollBack();
                return false;
            }
        }
        return true;
    }

    /**
     * edit corp by id function
     * @param integer $corpId
     * @param array $address
     * @return boolean
     */
    public function removeTargetAreaAddress($corpId = null, $address = null)
    {
        $listJisCd = $this->model->select('m_posts.jis_cd')
            ->leftjoin('m_corp_target_areas', function ($join) use ($corpId) {
                $join->on('m_corp_target_areas.jis_cd', '=', 'm_posts.jis_cd');
                $join->on('m_corp_target_areas.corp_id', '=', DB::raw($corpId));
            })
            ->whereIn('m_posts.address1', $address)
            ->groupBy('m_posts.jis_cd')
            ->orderBy('m_posts.jis_cd', 'asc')->pluck('m_posts.jis_cd')->toarray();
        DB::table('m_corp_target_areas')
            ->where('corp_id', '=', $corpId)
            ->whereIn('jis_cd', $listJisCd)->delete();
        return true;
    }
    /**
     * @param array $data
     * @return mixed|string
     */
    public function getTargetArea($data = [])
    {
        $query = $this->model->select('jis_cd');

        if (!empty($data['address1'])) {
            $query->where('address1', getDivTextJP('prefecture_div', $data['address1']));
        }

        if (!empty($data['address2'])) {
            $upperAddress2 = $data['address2'];
            $upperAddress2 = str_replace('ヶ', 'ケ', $upperAddress2);
            $upperAddress2 = str_replace('ﾉ', 'ノ', $upperAddress2);
            $upperAddress2 = str_replace('ﾂ', 'ツ', $upperAddress2);
            $lowerAddress2 = $data['address2'];
            $lowerAddress2 = str_replace('ケ', 'ヶ', $lowerAddress2);
            $lowerAddress2 = str_replace('ノ', 'ﾉ', $lowerAddress2);
            $lowerAddress2 = str_replace('ツ', 'ﾂ', $lowerAddress2);

            $query->where(
                function ($sqlQuery) use ($upperAddress2, $lowerAddress2) {
                    return $sqlQuery->where('address2', $upperAddress2)
                        ->orWhere('address2', $lowerAddress2);
                }
            );
        }

        $result = $query->groupBy('jis_cd')->first();
        if (isset($result)) {
            $result = $result->toarray();
            return !empty($result['jis_cd']) ? $result['jis_cd'] : '';
        }

        return '';
    }

    /**
     * @param string $address1
     * @return mixed
     */
    public function findByAddress1($address1)
    {
        return $this->model->select('jis_cd')->where('address1', $address1)->distinct()->get();
    }

    /**
     * @param integer $zipCode
     * @return \Illuminate\Support\Collection
     */
    public function searchAddressByZip($zipCode)
    {
        $result = DB::table('m_posts')
            ->select(
                [
                DB::raw('SUBSTR(m_posts.jis_cd, 1, 2) AS m_posts_jis_cd'),
                'm_posts.address2',
                'm_posts.address3'
                ]
            )
            ->where('m_posts.post_cd', '=', $zipCode)
            ->first();
        return $result;
    }

    /**
     * @param array $prefName
     * @return array|mixed
     */
    public function getJiscdByPrefName($prefName = [])
    {
        $result = $this->model->select('jis_cd')
            ->whereIn("address1", $prefName)
            ->distinct()->get()->toArray();
        return $result;
    }

    /**
     * @param integer $id
     * @param integer $address1Cd
     * @return array|mixed
     */
    public function findByCorpIdAndPrefecturalCode($id = null, $address1Cd = null)
    {
        $result = MPost::select('m_posts.address2', 'm_posts.jis_cd', 'm_corp_target_areas.corp_id')
            ->leftjoin('m_corp_target_areas', function ($join) use ($id) {
                $join->on('m_corp_target_areas.jis_cd', '=', 'm_posts.jis_cd');
                $join->where('m_corp_target_areas.corp_id', '=', $id);
            })
            ->where(DB::raw('SUBSTR(m_posts.jis_cd, 1, 2)'), $address1Cd)
            ->groupBy('m_posts.jis_cd', 'm_posts.address2', 'm_corp_target_areas.corp_id')
            ->orderBy('m_posts.jis_cd', 'asc')
            ->get()
            ->toarray();
        return $result;
    }

    /**
     * get jscd by address1 and address 2
     *
     * @author thaihv
     * @param  string $address1
     * @param  string $lAddress2
     * @param  string $uAddress2
     * @return MPost
     */
    public function getJiscdByAddress($address1, $lAddress2, $uAddress2)
    {
        $mPost = $this->model->where('address1', $address1);
        if (!empty($lAddress2)) {
            $mPost = $mPost->where(function ($wh) use ($lAddress2, $uAddress2) {
                $wh->orWhere('address2', $lAddress2)
                    ->orWhere('address2', $uAddress2);
            });
        }
        $mPost = $mPost->select('jis_cd')->groupBy('jis_cd')->first();
        return $mPost;
    }

    /**
     * @param integer $corpId
     * @param string $address1
     * @return \Illuminate\Support\Collection|mixed
     */
    public function findByAddress1AndCorpId($corpId, $address1)
    {
        return $this->model->where('m_posts.address1', $address1)
            ->leftJoin(
                'm_corp_target_areas',
                function ($join) use ($corpId) {
                    $join->on('m_corp_target_areas.jis_cd', '=', 'm_posts.jis_cd');
                    $join->where('m_corp_target_areas.corp_id', $corpId);
                }
            )
            ->select('m_posts.address2', 'm_posts.jis_cd', 'm_corp_target_areas.corp_id')
            ->groupBy('m_posts.address2', 'm_posts.jis_cd', 'm_corp_target_areas.corp_id')
            ->orderBy('m_posts.jis_cd', 'asc')->get();
    }
}
