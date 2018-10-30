<?php

namespace App\Repositories\Eloquent;

use App\Models\MSiteCateogry;
use App\Repositories\MSiteCategoryRepositoryInterface;

/**
 * MSiteCategoryRepository
 *
 * @author cuongnguyenx
 */
class MSiteCategoryRepository extends SingleKeyModelRepository implements MSiteCategoryRepositoryInterface
{
    /**
     * @var MSiteCateogry
     */
    protected $model;

    /**
     * MSiteCategoryRepository constructor.
     *
     * @param MSiteCateogry $model
     */
    public function __construct(MSiteCateogry $model)
    {
        $this->model = $model;
    }

    /**
     * Init new object
     *
     * {@inheritDoc}
     *
     * @see \App\Repositories\Eloquent\BaseRepository::getBlankModel()
     */
    public function getBlankModel()
    {
        return new MSiteCateogry();
    }

    /**
     * Get categories by site id
     *
     * {@inheritDoc}
     *
     * @see \App\Repositories\MSiteCategoryRepositoryInterface::getCategoriesBySite()
     */
    public function getCategoriesBySite($siteId = null)
    {
        $query = $this->model->from('m_site_categories AS MSiteCategory')
            ->join(
                'm_categories AS MCategory',
                function ($join) {
                                    $join->on('MSiteCategory.category_id', '=', 'MCategory.id');
                }
            );

        if (!is_null($siteId)) {
            $query->where('MSiteCategory.site_id', $siteId);
        }

        $rows = $query->select(
            'MCategory.id AS MCategory__id',
            'MCategory.category_name AS MCategory__category_name'
        )
            ->get()->toArray();
        $result = [];

        foreach ($rows as $row) {
            $result[$row['MCategory__id']] = $row['MCategory__category_name'];
        }

        return $result;
    }
}
