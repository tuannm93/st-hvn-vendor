<?php

namespace App\Repositories\Eloquent;

use App\Models\MSite;
use App\Repositories\MSiteRepositoryInterface;
use Exception;

class MSiteRepository extends SingleKeyModelRepository implements MSiteRepositoryInterface
{
    /**
     * @var MSite
     */
    protected $model;

    /**
     * MSiteRepository constructor.
     *
     * @param MSite $model
     */
    public function __construct(MSite $model)
    {
        $this->model = $model;
    }

    /**
     * @return \App\Models\Base|MSite|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new MSite();
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
        ];
    }

    /**
     * find max limit
     *
     * @param  array|mixed $data
     * @param  boolean     $throwable
     * @return integer
     * @throws Exception
     */
    public function findMaxLimit($data, $throwable = false)
    {
        if (empty($data)) {
            return 0;
        }
        if (is_null($data->site_id) || is_null($data->selection_system)) {
            throw  new Exception('site_idとselection_systemが必要です');
        }
        $result = $this->model
            ->select('manual_selection_limit', 'auction_selection_limit')
            ->where('id', $data->site_id)
            ->first();
        if (!$result) {
            if ($throwable) {
                throw  new Exception('確定上限数が取得できませんでした');
            }
            return 0;
        }
        if ($data->selection_system == 2 || $data->selection_system == 3) {
            return $result->auction_selection_limit;
        }
        return $result->manual_selection_limit;
    }

    /**
     * get site by site tel function
     *
     * @param integer $siteTel
     * @return array
     */
    public function searchMsite($siteTel)
    {
        $results = $this->model
            ->select('id')
            ->where('site_tel', '=', $siteTel)
            ->orderBy('id', 'asc')
            ->first();
        return $results;
    }

    /**
     * get list site function
     *
     * @return array
     */
    public function getList()
    {
        $list = $this->model
            ->select('site_name', 'id')
            ->orderBy('site_name', 'asc')
            ->get()
            ->toarray();
        $results = [];
        foreach ($list as $val) {
            $results[$val['id']] = $val['site_name'];
        }
        return $results;
    }

    /**
     * get name by id
     *
     * @param  integer $id
     * @return string
     */
    public function getNameById($id)
    {
        $item = $this->model->where('id', $id)->orderBy('id', 'desc')->first();

        return $item ? $item->site_name : '';
    }
    /**
     * Returns the site id that matches the cross-sight site determination (cross_site_flg) specified by the parameter as an array
     *
     * @author thaihv
     * @param  integer $flg
     * @return array
     */
    public function getCrossSiteFlg($flg)
    {
        return $this->model
            ->where('cross_site_flg', $flg)
            ->pluck('id')
            ->toArray();
    }

    /**
     * @return array|mixed
     */
    public function getListMSitesForDropDown()
    {
        return ['' => '--なし--'] +
            $this->model
            ->orderBy('site_name', 'asc')
            ->pluck('site_name', 'id')
            ->toArray();
    }

    /**
     * @param integer $id
     * @return mixed|static
     */
    public function findById($id)
    {
        return $this->model->find($id);
    }

    /**
     * @param integer $id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed|null|static|static[]
     */
    public function getWithCommissionType($id)
    {
        return $this->model->with('mCommissionType')->find($id);
    }

    /**
     * @param array $data
     * @param bool $throwable
     * @return int|mixed
     * @throws Exception
     */
    public function getMaxLimitDemandCreate($data, $throwable = false)
    {
        if (empty($data)) {
            return 0;
        }
        if (is_null($data['site_id']) || is_null($data['selection_system'])) {
            throw new Exception('site_idとselection_systemが必要です');
        }
        $result = $this->model
            ->select('manual_selection_limit', 'auction_selection_limit')
            ->where('id', $data['site_id'])
            ->first();
        if (!$result) {
            if ($throwable) {
                throw  new Exception('確定上限数が取得できませんでした');
            }
            return 0;
        }
        if ($data['selection_system'] == 2 || $data['selection_system'] == 3) {
            return $result->auction_selection_limit;
        }
        return $result->manual_selection_limit;
    }

    /**
     * Get site by site name
     * {@inheritDoc}
     *
     * @see \App\Repositories\MSiteRepositoryInterface::getSiteByName()
     */
    public function getSiteByName($siteName)
    {
        $result = $this->model
            ->where('site_name', $siteName)
            ->first();

        if ($result) {
            $result = $result->toArray();
        }

        return $result;
    }

    /**
     * @param integer $id
     * @return array
     */
    public function getSelectionLimit($id)
    {
        $result = $this->model
            ->select('manual_selection_limit', 'auction_selection_limit')
            ->find($id);
        return $result ? $result->toArray() : [];
    }

    /**
     * @author  thaihv
     * @param  int $siteTel site telephone
     * @return MSite
     */
    public function getFirstSiteByTel($siteTel)
    {
        return $this->model->where('site_tel', $siteTel)->select('id')->orderBy('id', 'ASC')->first();
    }

    /**
     * Return String list site
     * Use in DemandInfoService
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param integer $id
     * @return string
     */
    public function getListText($id)
    {
        $results = $this->model->where("id", $id)->orderBy("id", "asc")->get();
        $name = "";
        foreach ($results as $result) {
            if ($result->id == $id) {
                $name = $result->site_name;
            }
        }
        return $name;
    }
}
