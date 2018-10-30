<?php

namespace App\Repositories\Eloquent;

use App\Models\CorpCategoryApplication;
use App\Repositories\CorpCategoryApplicationRepositoryInterface;

class CorpCategoryApplicationRepository extends SingleKeyModelRepository implements CorpCategoryApplicationRepositoryInterface
{
    /**
     * @var CorpCategoryApplication
     */
    protected $model;

    /**
     * CorpCategoryApplicationRepository constructor.
     *
     * @param CorpCategoryApplication $model
     */
    public function __construct(CorpCategoryApplication $model)
    {
        $this->model = $model;
    }

    /**
     * @return \App\Models\Base|CorpCategoryApplication|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new CorpCategoryApplication();
    }
}
