<?php
namespace App\Repositories\Eloquent;

use App\Models\MoneyCorrespond;
use App\Repositories\MoneyCorrespondRepositoryInterface;
use Illuminate\Support\Facades\DB;

class MoneyCorrespondRepository extends SingleKeyModelRepository implements MoneyCorrespondRepositoryInterface
{
    /**
     * @var MoneyCorrespond
     */
    protected $model;

    /**
     * MoneyCorrespondRepository constructor.
     *
     * @param MoneyCorrespond $model
     */
    public function __construct(MoneyCorrespond $model)
    {
        $this->model = $model;
    }

    /**
     * @return \App\Models\Base|MoneyCorrespond|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new MoneyCorrespond();
    }

    /**
     * @param $id
     * @return int|mixed
     */
    public function deleteMoneyRecord($id)
    {
        return $this->model->destroy($id);
    }

    /**
     * get list money correspond
     *
     * @param integer $corpId
     * @param  string $nominee
     * @param  string $orderBy
     * @return mixed
     */
    public function getMoneyCorrespondDataInitial($corpId, $nominee = null, $orderBy = null)
    {
        return $this->model->where('corp_id', $corpId)
            ->when(
                $nominee,
                function ($query) use ($nominee) {
                    return $query->where(DB::raw('Z2h_kana(nominee)'), 'like', '%' . $nominee .'%');
                }
            )->orderBy('payment_date', ($orderBy === 'asc') ? 'asc' : 'desc')
            ->paginate(100);
    }

    /**
     * @param array $data
     * @return $this|\App\Models\Base|bool|\Illuminate\Database\Eloquent\Model
     */
    public function create($data)
    {
        return $this->model->create($data);
    }
}
