<?php

namespace App\Repositories\Eloquent;

use App\Models\MCategory;
use App\Repositories\AgreementAdminCategoryRepositoryInterface;
use App\Helpers\Util;
use Illuminate\Support\Facades\DB;

class AgreementAdminCategoryRepository extends SingleKeyModelRepository implements AgreementAdminCategoryRepositoryInterface
{

    /**
     * @var MCategory
     */
    protected $model;

    /**
     * AgreementAdminCategoryRepository constructor.
     *
     * @param MCategory $category
     */
    public function __construct(MCategory $category)
    {
        $this->model = $category;
    }

    /**
     * override getModelClassName method of SingleKeyModelRepository to get model name
     * @return string
     */
    public function getModelClassName()
    {
        return get_class($this->model);
    }

    /**
     * get joined data of table m_categories with category_license_link, license and m_genres
     * @return object
     */
    public function getAllJoinedMCategory()
    {
        $query = $this->model
            ->select(
                'm_categories.id',
                'm_genres.genre_name',
                'm_categories.category_name',
                DB::raw(
                    "(CASE m_categories.license_condition_type"
                    . " WHEN '" . MCategory::AND_LICENSE_CONDITION . "' THEN '" . MCategory::LICENSE_CONDITION_TYPE[MCategory::AND_LICENSE_CONDITION]
                    . "' ELSE '" . MCategory::LICENSE_CONDITION_TYPE[MCategory::OR_LICENSE_CONDITION] . "' END) AS license_condition_type_converted"
                )
            )
            ->selectRaw('string_agg(DISTINCT license.name, \',\') AS license_name')
            ->leftJoin('category_license_link', 'category_license_link.category_id', '=', 'm_categories.id')
            ->leftJoin('license', 'license.id', '=', 'category_license_link.license_id')
            ->join('m_genres', 'm_genres.id', '=', 'm_categories.genre_id')
            ->groupBy(
                [
                'm_categories.id',
                'm_genres.id'
                ]
            );
        return $query;
    }

    /**
     * get record by id
     * @param integer $id
     * @return array
     */
    public function getById($id)
    {
        return $this->model->where('id', $id)->first();
    }
}
