<?php

namespace App\Repositories\Eloquent;

use App\Models\MTime;
use App\Repositories\MTimeRepositoryInterface;

class MTimeRepository extends SingleKeyModelRepository implements MTimeRepositoryInterface
{
    /**
     * @var MTime
     */
    protected $model;

    /**
     * MTimeRepository constructor.
     *
     * @param MTime $model
     */
    public function __construct(MTime $model)
    {
        $this->model = $model;
    }

    /**
     * @return \App\Models\Base|MTime|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new MTime();
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
     * get by item category
     *
     * @param  string $itemCategory
     * @return array
     */
    public function getByItemCategory($itemCategory)
    {
        $data = $this->model
            ->select('item_hour_date', 'item_minute_date')
            ->where('item_category', $itemCategory)
            ->orderBy('item_id', 'desc')
            ->get();
        $list = [];
        foreach ($data as $key => $value) {
            $list[$key+ 1]['item_hour_date'] = $value->item_hour_date;
            $list[$key+ 1]['item_minute_date'] = $value->item_minute_date;
        }
        return $list;
    }

    /**
     * get support message time
     *
     * @return string
     */
    public function getSupportMessageTime()
    {
        $time = $this->model
            ->where('item_category', 'support_message')
            ->first();
        return !empty($time->item_hour_date) ? $time->item_hour_date : 0;
    }

    /**
     * @param integer $id
     * @param array $data
     * @return mixed
     */
    public function updateData($id, $data)
    {
        return $this->model->where('id', $id)->update($data);
    }

    /**
     * get item by item category follow tel
     *
     * @return array object
     */
    public function getByItemCategoryFollowTel()
    {
        return $this->model
            ->where('item_category', 'follow_tel')
            ->get();
    }

    /**
     * find by item detail and item category
     *
     * @param  string $itemDetail
     * @param  string $itemCategory
     * @return object
     */
    public function findByItemDetailAndItemCategory($itemDetail, $itemCategory)
    {
        return $this->model
            ->where('item_detail', $itemDetail)
            ->where('item_category', $itemCategory)
            ->first();
    }
}
