<?php

namespace App\Services;

use App\Repositories\AutoCommissionCorpRepositoryInterface;
use App\Repositories\MCorpRepositoryInterface;
use App\Repositories\MGenresRepositoryInterface;
use App\Repositories\MPostRepositoryInterface;
use Exception;

class AutoCommissionCorpService
{
    /**
     * @var MCorpRepositoryInterface
     */
    protected $mCorp;
    /**
     * @var AutoCommissionCorpRepositoryInterface
     */
    protected $autoCommissionCorp;
    /**
     * @var MGenresRepositoryInterface
     */
    protected $mGenres;
    /**
     * @var MPostRepositoryInterface
     */
    protected $mPost;

    /**
     * AutoCommissionCorpService constructor.
     *
     * @param MPostRepositoryInterface              $mPostInterface
     * @param MCorpRepositoryInterface              $mCorpRepository
     * @param AutoCommissionCorpRepositoryInterface $autoCommissionCorp
     * @param MGenresRepositoryInterface            $mGenres
     */
    public function __construct(
        MPostRepositoryInterface $mPostInterface,
        MCorpRepositoryInterface $mCorpRepository,
        AutoCommissionCorpRepositoryInterface $autoCommissionCorp,
        MGenresRepositoryInterface $mGenres
    ) {
        $this->mCorp = $mCorpRepository;
        $this->autoCommissionCorp = $autoCommissionCorp;
        $this->mGenres = $mGenres;
        $this->mPost = $mPostInterface;
    }


    /**
     * prepare data for view crop_select
     *
     * @return mixed
     */
    public function prepareDataForViewCorpSelect()
    {
        $listPrefecture = \Config::get('datacustom.prefecture_div');
        $listGenre = ['-1' => __('auto_commission_corp.none')];
        $listGenre = $listGenre + $this->mGenres->getList(true);
        $data['listPref'] = $listPrefecture;
        $data['listGenre'] = $listGenre;
        return $data;
    }

    /**
     * get list crop id by category and pref
     *
     * @param  array $listIdCate
     * @param  array $listIdPref
     * @return array
     */
    public function getListCorpByCategoryPref($listIdCate, $listIdPref)
    {
        $listPrefecture = \Config::get('datacustom.prefecture_div');
        $listNamePref = [];
        foreach ($listIdPref as $obj) {
            array_push($listNamePref, $listPrefecture[$obj]);
        }
        $listCorp = $this->mCorp->getListByCategoryIdsAndAddress1($listIdCate, $listNamePref);
        return $listCorp;
    }

    /**
     * @param $listIdCate
     * @param $listIdPref
     * @param $listCommissionCorp
     * @param $listSelectCorp
     * @return bool
     */
    public function editListCorpSelect(
        $listIdCate,
        $listIdPref,
        $listCommissionCorp,
        $listSelectCorp
    ) {
        $bSuccess = true;
        $listPrefecture = \Config::get('datacustom.prefecture_div');
        if (is_array($listIdPref) && count($listIdPref) > 0) {
            $listNamePref = [];
            foreach ($listIdPref as $obj) {
                array_push($listNamePref, $listPrefecture[$obj]);
            }
            $listJiscd = $this->mPost->getJiscdByPrefName($listNamePref);
            $bSuccess = $this->autoCommissionCorp->deleteByCateAndPref($listIdCate, $listIdPref);
            if (is_array($listIdCate) && count($listIdCate) > 0) {
                $bSuccess = $this->autoCommissionCorp->addCorpInfor(
                    $listCommissionCorp,
                    $listSelectCorp,
                    $listIdCate,
                    $listJiscd
                );
            }
        }
        return $bSuccess;
    }

    /**
     * @param $arrGenreId
     * @return mixed
     */
    public function getCommissionCorpBelongToGenreId($arrGenreId)
    {
        return $this->autoCommissionCorp->findByGenreId($arrGenreId);
    }

    /**
     * @param array $arrGenreId
     * @param array $arrCategoryId
     * @return mixed
     */
    public function pluckListGenreRelated($arrGenreId = [], $arrCategoryId = [])
    {
        $query = $this->mGenres->queryListGenreRelated($arrGenreId, $arrCategoryId);
        $list = $query->pluck('m_genres.genre_name', 'm_genres.id');
        return $list;
    }

    /**
     * @param array $arrGenreId
     * @param array $arrCategoryId
     * @return mixed
     */
    public function getListGenreRelated($arrGenreId = [], $arrCategoryId = [])
    {
        $query = $this->mGenres->queryListGenreRelated($arrGenreId, $arrCategoryId);
        $list = $query->select(
            'm_genres.id',
            'm_genres.genre_name',
            'm_categories.id',
            'm_categories.category_name',
            'select_genres.select_type'
        )->get();
        return $list;
    }

    /**
     * Search auto commission corp with list genre id
     * @param array $arrGenreId
     * @return array
     */
    public function searchAutoCommissionCorp($arrGenreId)
    {
        foreach (config('constant.state_list') as $key => $value) {
            $data['pref_list'][] = [
                'pref_id' => $key,
                'pref_name' => $value,
            ];
        }

        try {
            $data['corp'] = $this->getCommissionCorpBelongToGenreId($arrGenreId);
            $data['category'] = $this->getListGenreRelated($arrGenreId, []);
            $list = config('datacustom.selection_type');
            $selectionSystemList = [];
            foreach ($list as $key => $value) {
                $selectionSystemList[$key] = trans('auto_commission_corp.selection_type.' . $value);
            }

            $data['selection'] = $selectionSystemList;
            $data['status'] = 200;
        } catch (Exception $e) {
            $data['status'] = 500;
        }
        return $data;
    }

    /**
     * Get all auto commission corp
     *
     * @return int
     */
    public function getAllAutoCommissionCorp()
    {
        foreach (config('constant.state_list') as $key => $value) {
            $data['pref_list'][] = [
                'pref_id' => $key,
                'pref_name' => $value,
            ];
        }

        try {
            $data['corp'] = $this->getCommissionCorpBelongToGenreId([]);
            $autoCommissionCorpList = [];
            foreach ($data['corp'] as $key => $value) {
                $autoCommissionCorpList[$value->category_id] = $value->category_id;
            }
            $data['category'] = $this->getListGenreRelated([], $autoCommissionCorpList);
            $list = config('datacustom.selection_type');
            $selectionSystemList = [];
            foreach ($list as $key => $value) {
                $selectionSystemList[$key] = trans('auto_commission_corp.selection_type.' . $value);
            }
            $data['selection'] = $selectionSystemList;
            $data['status'] = 200;
        } catch (Exception $e) {
            $data['status'] = 500;
        }
        return $data;
    }
}
