<?php

namespace App\Repositories\Eloquent;

use App\Models\MCorpCategoriesTemp;
use App\Repositories\MCorpCategoriesTempRepositoryInterface;
use Auth;
use DB;
use Illuminate\Database\Query\Builder;
use Log;

class MCorpCategoriesTempRepository extends SingleKeyModelRepository implements MCorpCategoriesTempRepositoryInterface
{
    /**
     * @var MCorpCategoriesTemp
     */
    protected $model;

    /**
     * MCorpCategoriesTempRepository constructor.
     *
     * @param MCorpCategoriesTemp $model
     */
    public function __construct(MCorpCategoriesTemp $model)
    {
        $this->model = $model;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [];
    }

    /**
     * get by corp id and temp id
     *
     * @param  integer $corpId
     * @param  integer $tempId
     * @return array object
     */
    public function getByCorpIdAndTempId($corpId, $tempId)
    {
        return $this->model
            ->select(
                'm_corp_categories_temp.*',
                'm_corp_categories.id as mcc_id',
                'm_corp_categories as mcc_order_fee',
                'm_corp_categories.order_fee_unit as mcc_order_fee_unit',
                'm_corp_categories.introduce_fee as mcc_introduce_fee',
                'm_corp_categories.note as mcc_note',
                'm_corp_categories.select_list as mcc_select_list',
                'm_corp_categories.corp_commission_type as mcc_corp_commission_type'
            )
            ->leftJoin(
                'm_corp_categories',
                function ($join) {
                    $join->on('m_corp_categories.corp_id', '=', 'm_corp_categories_temp.corp_id');
                    $join->on('m_corp_categories.category_id', '=', 'm_corp_categories_temp.category_id');
                    $join->on('m_corp_categories.genre_id', '=', 'm_corp_categories_temp.genre_id');
                }
            )
            ->where('m_corp_categories_temp.corp_id', $corpId)
            ->where('m_corp_categories_temp.temp_id', $tempId)
            ->orderBy('m_corp_categories_temp.id', 'desc')
            ->get();
    }

    /**
     * save many data
     *
     * @param  array object $items
     * @return boolean
     */
    public function saveManyData($items)
    {
        foreach ($items as $item) {
            if (!$item->save()) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param integer $corpId
     * @param integer $tempId
     * @return integer
     */
    public function countByCorpIdAndTempId($corpId, $tempId)
    {
        return $this->model->where(
            [
            ['corp_id', '=', $corpId],
            ['temp_id', '=', $tempId]
            ]
        )->count();
    }

    /**
     * @param integer $corpId
     * @param null           $tempId
     * @param integer $latestTempLink
     * @param null           $mCorpCategoryRepo
     * @param boolean        $getAll
     * @return array
     */
    public function findCategoryTempCopy($corpId, $tempId = null, $latestTempLink = null, $mCorpCategoryRepo = null, $getAll = false)
    {
        $count = $this->countByTempId($tempId);
        if ($count == 0) {
            if (!empty($latestTempLink)) {
                $results = $this->getMCorpCategoryGenreList($corpId, $latestTempLink['id']);
                $result = $this->setDataForSave($results, $tempId, $getAll, true);
            } else {
                $corpCategories = $mCorpCategoryRepo->getListByCorpId($corpId);
                $result = $this->setDataForSave($corpCategories, $tempId, $getAll);
            }
        } else {
            $results = $this->getMCorpCategoryGenreList($corpId, $tempId);
            $result = $this->setDataForSave($results, $tempId, $getAll);
        }
        return $result ? $result : [];
    }

    /**
     * @param array $data
     * @param integer $tempId
     * @param boolean $getAll
     * @param boolean $action
     * @return array
     */
    public function setDataForSave($data, $tempId, $getAll = false, $action = false)
    {
        if ($getAll) {
            return $data;
        }
        $date = date('Y-m-d H:i:s');
        $user = Auth::user()['user_id'];
        $result = [];
        foreach ($data as $k => $val) {
            $result[$k]['corp_id'] = $val['corp_id'];
            $result[$k]['genre_id'] = $val['genre_id'];
            $result[$k]['category_id'] = $val['category_id'];
            $result[$k]['order_fee'] = $val['order_fee'];
            $result[$k]['order_fee_unit'] = $val['order_fee_unit'];
            $result[$k]['introduce_fee'] = $val['introduce_fee'];
            $result[$k]['note'] = $val['note'];
            $result[$k]['select_list'] = $val['select_list'];
            $result[$k]['select_genre_category'] = $val['select_genre_category'];
            $result[$k]['target_area_type'] = $val['target_area_type'];
            $result[$k]['corp_commission_type'] = $val['corp_commission_type'];
            $result[$k]['temp_id'] = $tempId;
            $result[$k]['created'] = $date;
            $result[$k]['created_user_id'] = $user;
            $result[$k]['modified'] = $date;
            $result[$k]['modified_user_id'] = $user;
            $result[$k]['create_date'] = $date;
            $result[$k]['create_user_id'] = Auth::user()['id'];
            $result[$k]['update_date'] = $date;
            $result[$k]['update_user_id'] = Auth::user()['id'];
            if ($action) {
                $result[$k]['action'] = null;
            }
        }
        return $result;
    }

    /**
     * @param integer $tempId
     * @return mixed
     */
    public function countByTempId($tempId)
    {
        return $this->model->where('temp_id', $tempId)->count();
    }

    /**
     * @param integer $id
     * @param integer $tempId
     * @return mixed
     */
    public function getMCorpCategoryGenreList($id = null, $tempId = null)
    {
        $list = $this->model->leftJoin('m_genres', 'm_genres.id', '=', 'm_corp_categories_temp.genre_id')
            ->leftJoin('m_categories', 'm_categories.id', '=', 'm_corp_categories_temp.category_id')
            ->select(
                'm_corp_categories_temp.*',
                'm_genres.id as m_genres_id',
                'm_genres.genre_name',
                'm_genres.commission_type',
                'm_categories.category_name',
                'm_categories.hide_flg',
                'm_categories.disable_flg',
                DB::raw('(select m_sites.commission_type from m_site_genres INNER JOIN m_sites on m_site_genres.site_id = m_sites.id where m_site_genres.genre_id = m_genres.id order by m_sites.id limit 1) AS "m_sites_commission_type"')
            )
            ->where(
                [
                ['m_corp_categories_temp.corp_id', '=', $id],
                ['m_corp_categories_temp.temp_id', '=', $tempId],
                ['m_corp_categories_temp.delete_flag', '=', false]
                ]
            )
            ->orderBy('m_categories.category_name', 'asc')
            ->get();
        return $list;
    }

    /**
     * @param integer $corpId
     * @param integer $corpAgreementId
     * @return mixed
     */
    public function insertItem($corpId, $corpAgreementId)
    {
        return $this->model->insert(
            [
            'corp_id' => $corpId,
            'corp_agreement_id' => $corpAgreementId
            ]
        );
    }

    /**
     * @param integer $id
     * @param integer $tempId
     * @return \Illuminate\Support\Collection|mixed
     */
    public function getTempData($id, $tempId)
    {
        return $this->model->leftJoin(
            'm_corp_categories',
            function ($join) {
                $join->on('m_corp_categories.corp_id', '=', 'm_corp_categories_temp.corp_id');
                $join->on('m_corp_categories.category_id', '=', 'm_corp_categories_temp.category_id');
                $join->on('m_corp_categories.genre_id', '=', 'm_corp_categories_temp.genre_id');
            }
        )
            ->where(
                [
                ['m_corp_categories_temp.corp_id', $id],
                ['m_corp_categories_temp.temp_id', $tempId]
                ]
            )->select(
                'm_corp_categories_temp.*',
                'm_corp_categories.id as MCorpCategory.id',
                'm_corp_categories.order_fee as MCorpCategory.order_fee',
                'm_corp_categories.order_fee_unit as MCorpCategory.order_fee_unit',
                'm_corp_categories.introduce_fee as MCorpCategory.introduce_fee',
                'm_corp_categories.note as MCorpCategory.note',
                'm_corp_categories.select_list as MCorpCategory.select_list',
                'm_corp_categories.corp_commission_type as MCorpCategory.corp_commission_type'
            )
            ->orderBy('m_corp_categories_temp.id', 'desc')
            ->get();
    }

    /**
     * @param array $saveData
     * @return mixed
     */
    public function saveAll($saveData)
    {
        $this->model->corp_id = $saveData['corp_id'];
        $this->model->genre_id = $saveData['genre_id'];
        $this->model->category_id = $saveData['category_id'];
        $this->model->order_fee_unit = $saveData['order_fee_unit'];
        $this->model->introduce_fee = $saveData['introduce_fee'];
        $this->model->note = $saveData['note'];
        $this->model->modified_user_id = $saveData['modified_user_id'];
        $this->model->modified = $saveData['modified'];
        $this->model->created = $saveData['created'];
        $this->model->select_list = $saveData['select_list'];
        $this->model->select_genre_category = $saveData['select_genre_category'];
        $this->model->target_area_type = $saveData['target_area_type'];
        $this->model->version_no = $saveData['version_no'];
        $this->model->create_date = $saveData['create_date'];
        $this->model->create_user_id = $saveData['create_user_id'];
        $this->model->update_date = $saveData['update_date'];
        $this->model->update_user_id = $saveData['update_user_id'];
        $this->model->delete_date = $saveData['delete_date'];
        $this->model->delete_flag = $saveData['delete_flag'];
        $this->model->temp_id = $saveData['temp_id'];
        $this->model->action = $saveData['action'];
        $this->model->corp_commission_type = $saveData['corp_commission_type'];
        return $this->model->save();
    }

    /**
     * @param null $id
     * @param null $tempId
     * @param null $deleteFlag
     * @param null $disableFlg
     * @return mixed
     */
    public function findAllByCorpIdAndTempIdWithFlag($id = null, $tempId = null, $deleteFlag = null, $disableFlg = null)
    {
        $list = $this->model->select(
            'm_corp_categories_temp.id as m_corp_categories_temp_id',
            'm_corp_categories_temp.note as m_corp_categories_temp_note',
            'm_corp_categories_temp.*',
            'm_genres.*',
            'm_categories.*',
            DB::raw('(select m_sites.commission_type from m_site_genres INNER JOIN m_sites on m_site_genres.site_id = m_sites.id where m_site_genres.genre_id = m_genres.id order by m_sites.id limit 1) AS "m_sites_commission_type"')
        )->leftJoin('m_genres', 'm_genres.id', '=', 'm_corp_categories_temp.genre_id')
            ->leftJoin('m_categories', 'm_categories.id', '=', 'm_corp_categories_temp.category_id')
            ->where(
                [
                ['m_corp_categories_temp.corp_id', '=', $id],
                ['m_corp_categories_temp.temp_id', '=', $tempId],
                ]
            );
        if (!is_null($deleteFlag)) {
            $list = $list->where('m_corp_categories_temp.delete_flag', '=', $deleteFlag);
        }
        if (!is_null($deleteFlag)) {
            $list = $list->where('m_categories.disable_flg', '=', $disableFlg);
        }
        $list = $list->orderBy('m_categories.category_name', 'asc')->get();
        return $list;
    }

    /**
     * @return mixed
     */
    public function getCorpAgreementCategory()
    {
        $dataResult = $this->getQueryCorpAgreementCategory()->paginate(config('rits.list_limit'));
        return $dataResult;
    }

    /**
     * get query for corp agreement category
     *
     * @return object
     */
    public function getQueryCorpAgreementCategory()
    {
        $query = $this->model
            ->select(
                'm_corp_categories_temp.id',
                'corp_agreement_temp_link.corp_agreement_id',
                'm_corps.id AS m_corps_id',
                'm_corps.official_corp_name',
                'm_genres.id AS m_genres_id',
                'm_genres.genre_name',
                'm_categories.id AS m_categories_id',
                'm_categories.category_name',
                'm_corp_categories_temp.order_fee',
                DB::raw("CASE WHEN order_fee_unit = 0 THEN '円' WHEN order_fee_unit = 1 THEN '%' ELSE '' END AS custom_order_fee_unit"),
                'm_corp_categories_temp.introduce_fee',
                'm_corp_categories_temp.note',
                'm_corp_categories_temp.select_list',
                DB::raw("CASE WHEN m_corp_categories_temp.corp_commission_type = 1 THEN '成約ベース' WHEN m_corp_categories_temp.corp_commission_type = 2 THEN '紹介ベース' ELSE '' END AS custom_corp_commission_type"),
                DB::raw("CASE when action like 'Add%' then '追加' when action like 'Update%' then '変更' when action like 'Delete%' then '削除' else '' end AS custom_action_type"),
                DB::raw("CASE when action like 'Update%' THEN replace(replace(replace(replace(replace(replace(replace(action, 'Update:', ''), 'order_fee_unit', '受注手数料単位'), 'order_fee', '受注手数料'), 'note', '備考'), 'select_list', '専門性'), 'introduce_fee', '紹介手数料'), 'corp_commission_type', '取次形態') ELSE '' END AS custom_action"),
                'm_corp_categories_temp.modified'
            )

            ->leftJoin('corp_agreement_temp_link', 'm_corp_categories_temp.temp_id', '=', 'corp_agreement_temp_link.id')
            ->leftJoin('m_genres', 'm_corp_categories_temp.genre_id', '=', 'm_genres.id')
            ->leftJoin('m_categories', 'm_corp_categories_temp.category_id', '=', 'm_categories.id')
            ->join('m_corps', 'm_corp_categories_temp.corp_id', '=', 'm_corps.id')
            ->where('m_corp_categories_temp.action', '<>', '""')
            ->whereNotNull('m_corp_categories_temp.action')
            ->orderBy('m_corp_categories_temp.id', 'desc');
        return $query;
    }

    /**
     * @return mixed
     */
    public function getCsvCorpAgreementCategory()
    {
        return $this->getQueryCorpAgreementCategory()->get()->toarray();
    }

    /**
     * @param integer $id
     * @param integer $categoryId
     * @param integer $tempId
     * @param bool $deleteFlag
     * @return \Illuminate\Database\Eloquent\Model|mixed|null|object|static
     */
    public function findAllByCorpIdAndCateIdAndTempIdAndDelFlag(
        $id = null,
        $categoryId = null,
        $tempId = null,
        $deleteFlag = false
    ) {
        $list = $this->model->select(
            'm_corp_categories_temp.id as m_corp_categories_temp_id',
            'm_corp_categories_temp.*',
            'm_genres.*',
            'm_categories.*'
        )
            ->leftJoin('m_genres', 'm_genres.id', '=', 'm_corp_categories_temp.genre_id')
            ->leftJoin('m_categories', 'm_categories.id', '=', 'm_corp_categories_temp.category_id')
            ->where(
                [
                ['m_corp_categories_temp.corp_id', '=', $id],
                ['m_corp_categories_temp.category_id', '=', $categoryId],
                ['m_corp_categories_temp.temp_id', '=', $tempId],
                ['m_corp_categories_temp.delete_flag', '=', $deleteFlag]
                ]
            )
            ->orderBy('m_corp_categories_temp.id', 'desc')
            ->first();
        return $list;
    }

    /**
     * @param $idTemp
     * @return int|mixed
     */
    public function getCountByTempId($idTemp)
    {
        return $this->model->where('temp_id', '=', $idTemp)->count();
    }

    /**
     * @param array $data
     * @return bool|mixed
     */
    public function saveWithData($data)
    {
        if (!is_array($data)) {
            $data = json_decode(json_encode($data), true);
        }
        $bCreateNew = true;
        if (!isset($data['id']) || empty($data['id'])) {
            $model = $this->getBlankModel();
        } else {
            $bCreateNew = false;
            $model = $this->model->find((int)$data['id']);
        }
        if (isset($model)) {
            $this->setInfoData($model, $data);
            $this->setInfoUpdate($model, $data, $bCreateNew);
            return $model->save();
        }
        return true;
    }

    /**
     * set info data
     * @param object $model
     * @param array $data
     */
    private function setInfoData(&$model, $data)
    {
        $model->corp_id = (int)$data['corp_id'];
        $model->genre_id = (int)$data['genre_id'];
        $model->category_id = (int)$data['category_id'];
        $arrayField = ['temp_id', 'order_fee', 'order_fee_unit', 'introduce_fee'];
        foreach ($arrayField as $value) {
            if (isset($data[$value])) {
                $model->$value = (int)$data[$value];
            }
        }
        if (isset($data['note'])) {
            $model->note = $data['note'];
        }
        if (isset($data['select_list'])) {
            $model->select_list = $data['select_list'];
        }
        if (isset($data['selectOption']) && isset($data['selectOption'])) {
            $model->select_list = $data['selectOption'];
        }
        if (isset($data['action'])) {
            $model->action = $data['action'];
        }
        $model->corp_commission_type = isset($data['corp_commission_type']) ?
            (int)$data['corp_commission_type'] : 0;
    }

    /**
     * set info update
     * @param object $model
     * @param array $data
     * @param boolean $bCreateNew
     */
    private function setInfoUpdate(&$model, $data, $bCreateNew)
    {
        $timeUpdate = date('Y-m-d H:i:s');
        $model->modified_user_id =  \Auth::user()->user_id;
        $model->modified = $timeUpdate;
        $model->update_user_id = \Auth::user()->id;
        $model->update_date = $timeUpdate;
        if ($bCreateNew) {
            $model->created = $timeUpdate;
            $model->created_user_id =  \Auth::user()->user_id;
            $model->create_user_id = \Auth::user()->id;
            $model->create_date = $timeUpdate;
        }
        $model->delete_flag = !empty($data['delete_flag']) ? $data['delete_flag'] : false;
    }

    /**
     * @return \App\Models\Base|MCorpCategoriesTemp|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new MCorpCategoriesTemp();
    }

    /**
     * @param integer $categoryId
     * @param integer $tempId
     * @return array|mixed
     */
    public function findAllByCategoryIdAndTempId($categoryId, $tempId)
    {
        $query = $this->model->select('*')
            ->where('temp_id', '=', $tempId)
            ->where('delete_flag', '=', false)
            ->where(
                function ($where) use ($categoryId) {
                    /**
                * @var Builder $where
                */
                    if (!isset($categoryId) || count($categoryId) == 0) {
                        $where->whereNull('category_id');
                    } else {
                        $where->whereIn('category_id', $categoryId);
                    }
                }
            )->get()->toArray();
        return $query;
    }

    /**
     * @param integer $idCate
     * @return array|mixed
     */
    public function getListCategoryIdById($idCate)
    {
        return $this->model->select('category_id')
            ->where('id', '=', $idCate)
            ->pluck('category_id')->toArray();
    }

    /**
     * @param array $listCopyCate
     * @param integer $idCorp
     * @param integer $idTemp
     * @return array|mixed
     */
    public function getListIdBy($listCopyCate, $idCorp, $idTemp)
    {
        return $this->model->select('id')
            ->whereIn('category_id', $listCopyCate)
            ->where('temp_id', '=', $idTemp)
            ->where('corp_id', '=', $idCorp)
            ->pluck('id')->toArray();
    }

    /**
     * @param integer $id
     * @return array|mixed
     */
    public function getById($id)
    {
        return $this->model->select('*')
            ->where('id', '=', $id)
            ->first()->toArray();
    }

    /**
     * @param integer $idCorp
     * @param integer $idGenre
     * @param integer $idCategory
     * @param integer $idTemp
     * @return \Illuminate\Database\Eloquent\Model|mixed|null|object|static
     */
    public function getIdFirstBy($idCorp, $idGenre, $idCategory, $idTemp)
    {
        return $this->model->select('id')
            ->where('genre_id', '=', $idGenre)
            ->where('temp_id', '=', $idTemp)
            ->where('corp_id', '=', $idCorp)
            ->where('category_id', '=', $idCategory)
            ->where('delete_flag', '=', false)
            ->first();
    }

    /**
     * @param integer $id
     * @param integer $categoryId
     * @param integer $tempId
     * @param bool $deleteFlag
     * @return \Illuminate\Database\Eloquent\Model|mixed|null|object|static
     */
    public function getFirstByCorpIdAndCateIdAndTempIdAndDelFlag(
        $id = null,
        $categoryId = null,
        $tempId = null,
        $deleteFlag = false
    ) {
        $list = $this->model->select(
            'm_corp_categories_temp.id as m_corp_categories_temp_id',
            'm_corp_categories_temp.*',
            'm_genres.*',
            'm_categories.*'
        )
            ->leftJoin('m_genres', 'm_genres.id', '=', 'm_corp_categories_temp.genre_id')
            ->leftJoin('m_categories', 'm_categories.id', '=', 'm_corp_categories_temp.category_id')
            ->where(
                [
                ['m_corp_categories_temp.corp_id', '=', $id],
                ['m_corp_categories_temp.category_id', '=', $categoryId],
                ['m_corp_categories_temp.temp_id', '=', $tempId],
                ['m_corp_categories_temp.delete_flag', '=', $deleteFlag]
                ]
            )
            ->orderBy('m_corp_categories_temp.id', 'desc')
            ->first();
        return $list;
    }

    /**
     * @param integer $id
     * @param integer $type
     * @return mixed|void
     */
    public function updateTargetAreaType($id, $type)
    {
        try {
            DB::beginTransaction();
            $model = $this->model->find((int)$id);
            if (isset($model)) {
                $model->id = $id;
                $model->target_area_type = $type;
                $model->save();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
        }
    }
}
