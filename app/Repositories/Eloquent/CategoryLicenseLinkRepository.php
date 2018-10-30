<?php

namespace App\Repositories\Eloquent;

use App\Models\CategoryLicenseLink;
use App\Repositories\CategoryLicenseLinkRepositoryInterface;

class CategoryLicenseLinkRepository extends SingleKeyModelRepository implements CategoryLicenseLinkRepositoryInterface
{

    /**
     * @var CategoryLicenseLink
     */
    protected $model;

    /**
     * CategoryLicenseLinkRepository constructor.
     *
     * @param CategoryLicenseLink $model
     */
    public function __construct(CategoryLicenseLink $model)
    {
        $this->model = $model;
    }

    /**
     * override getModelClassName method of SingleKeyModelRepository to get model name
     *
     * @return string
     */
    public function getModelClassName()
    {
        return get_class($this->model);
    }

    /**
     * get license's id list from category_license_link table by category_id
     *
     * @param integer $id
     * @return mixed|void
     */
    public function getLicenseIdsByCategoryId($id)
    {
        return $this->model->select('license_id')->where('category_id', '=', $id)->get();
    }

    /**
     * delete record(s) of category_license_link table with where (category_id = $categoryId and license_id in $licenseId)
     * @param integer $categoryId
     * @param integer $licenseId
     * @return mixed|void
     */
    public function deleteByCategoryIdAndLicenseId($categoryId, $licenseId)
    {
        $this->model->where('category_id', $categoryId)->whereIn('license_id', $licenseId)->delete();
    }

    /**
     * @param integer $licenseId
     * @return mixed|void
     */
    public function deleteByLicenseId($licenseId)
    {
        $this->model->where('license_id', $licenseId)->delete();
    }
}
