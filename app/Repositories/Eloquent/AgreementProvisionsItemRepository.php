<?php


namespace App\Repositories\Eloquent;

use App\Models\AgreementProvisionItem;
use App\Repositories\AgreementProvisionsItemRepositoryInterface;

class AgreementProvisionsItemRepository extends SingleKeyModelRepository implements AgreementProvisionsItemRepositoryInterface
{
    /**
     * @var AgreementProvisionItem
     */
    protected $model;

    /**
     * AgreementProvisionsItemRepository constructor.
     *
     * @param AgreementProvisionItem $agreementProvisionItem
     */
    public function __construct(AgreementProvisionItem $agreementProvisionItem)
    {
        $this->model = $agreementProvisionItem;
    }

    /**
     * @param string $column
     * @param string $value
     * @return bool|null
     * @throws \Exception
     */
    public function deleteByColumn($column, $value)
    {
        return $this->model->where($column, $value)->delete();
    }

    /**
     * @param integer $id
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function findById($id)
    {
        return $this->model->where('id', $id)->first();
    }

    /**
     * @return string
     */
    public function getModelClassName()
    {
        return get_class($this->model);
    }
}
