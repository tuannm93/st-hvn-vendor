<?php

namespace App\Repositories\Eloquent;

use App\Models\MInquiry;
use App\Repositories\MInquiryRepositoryInterface;

class MInquiryRepository extends SingleKeyModelRepository implements MInquiryRepositoryInterface
{
    /**
     * @var MInquiry
     */
    protected $model;

    /**
     * MInquiryRepository constructor.
     *
     * @param MInquiry $model
     */
    public function __construct(MInquiry $model)
    {
        $this->model = $model;
    }

    /**
     * @return \App\Models\Base|MInquiry|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new  MInquiry();
    }

    /**
     * @param null $category
     * @return array|mixed
     */
    public function getListInquiryByCategory($category = null)
    {
        $query = $this->model->select('*');
        if (!is_null($category)) {
            $query->where('category_id', '=', (int)$category);
        } else {
            $query->whereNull('category_id');
        }
        $query->orderBy('id', 'asc');
        return $query->get()->toArray();
    }
}
