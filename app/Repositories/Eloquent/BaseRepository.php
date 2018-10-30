<?php

namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Base;
use Illuminate\Support\Facades\Schema;

class BaseRepository implements BaseRepositoryInterface
{
    /**
     * @var boolean
     */
    protected $cacheEnabled = false;

    /**
     * @var string
     */
    protected $cachePrefix = 'model';

    /**
     * @var integer
     */
    protected $cacheLifeTime = 60; // Minutes

    /**
     * @return Base[]|array|Collection|\Traversable
     */
    public function getEmptyList()
    {
        return new Collection();
    }

    /**
     * @return string
     */
    public function getModelClassName()
    {
        $model = $this->getBlankModel();

        return get_class($model);
    }

    /**
     * @return Base|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new Base();
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
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator|\Illuminate\Validation\Validator
     */
    public function validator(array $data)
    {
        return \Validator::make($data, $this->rule());
    }

    /**
     * @param null $order
     * @param null $direction
     * @return Base[]|array|mixed|\Traversable
     */
    public function all($order = null, $direction = null)
    {
        $model = $this->getModelClassName();
        if (!empty($order)) {
            $direction = empty($direction) ? 'asc' : $direction;

            return $model::orderBy($order, $direction)->get();
        }

        return $model::all();
    }

    /**
     * @param null $order
     * @param null $direction
     * @return Base[]|array|mixed|\Traversable
     */
    public function allEnabled($order = null, $direction = null)
    {
        $model = $this->getModelClassName();
        $query = $model::where('is_enabled', '=', true);
        if (!empty($order)) {
            $direction = empty($direction) ? 'asc' : $direction;
            $query = $query->orderBy($order, $direction);
        }

        return $query->get();
    }

    /**
     * @param string $order
     * @param string $direction
     * @param int    $offset
     * @param int    $limit
     * @return Base[]|array|mixed|\Traversable
     */
    public function get($order = 'id', $direction = 'asc', $offset = 0, $limit = 20)
    {
        $model = $this->getModelClassName();

        return $model::orderBy($order, $direction)->skip($offset)->take($limit)->get();
    }

    /**
     * @param string $order
     * @param string $direction
     * @param int    $offset
     * @param int    $limit
     * @return Base[]|array|mixed|\Traversable
     */
    public function getEnabled($order = 'id', $direction = 'asc', $offset = 0, $limit = 20)
    {
        $model = $this->getModelClassName();

        return $model::where('is_enabled', '=', true)->orderBy($order, $direction)->skip($offset)->take($limit)->get();
    }

    /**
     * @return int|mixed
     */
    public function count()
    {
        $model = $this->getModelClassName();

        return $model::count();
    }

    /**
     * @return int|mixed
     */
    public function countEnabled()
    {
        $model = $this->getModelClassName();

        return $model::where('is_enabled', '=', true)->count();
    }

    /**
     * @param Base[] $models
     * @return array
     */
    public function getAPIArray($models)
    {
        $ret = [];
        foreach ($models as $model) {
            $ret[] = $model->toAPIArray();
        }

        return $ret;
    }

    /**
     * @param \Illuminate\Support\Collection $collection
     * @param string                         $value
     * @param null                           $key
     * @return \Illuminate\Support\Collection|static
     */
    public function pluck($collection, $value, $key = null)
    {
        $items = [];
        foreach ($collection as $model) {
            if (empty($key)) {
                $items[] = $model->$value;
            } else {
                $items[$model->$key] = $model->$value;
            }
        }

        return Collection::make($items);
    }

    /**
     * @param integer[] $ids
     *
     * @return string
     */
    protected function getCacheKey($ids)
    {
        $key = $this->cachePrefix;
        foreach ($ids as $id) {
            $key .= '-' . $id;
        }

        return $key;
    }

    /**
     * @param \Illuminate\Database\Query\Builder $query
     * @param string                             $order
     * @param string                             $direction
     * @param integer                            $offset
     * @param integer                            $limit
     * @param string[]                           $orderCandidates
     * @param string                             $orderDefault
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getWithQueryBuilder(
        $query,
        $order,
        $direction,
        $offset,
        $limit,
        $orderCandidates = [],
        $orderDefault = 'id'
    ) {
        $order = strtolower($order);
        $direction = strtolower($direction);
        $offset = intval($offset);
        $limit = intval($limit);
        $order = in_array($order, $orderCandidates) ? $order : strtolower($orderDefault);
        $direction = in_array($direction, ['asc', 'desc']) ? $direction : 'asc';

        if ($limit <= 0) {
            $limit = 10;
        }
        if ($offset < 0) {
            $offset = 0;
        }

        return $query->orderBy($order, $direction)->offset($offset)->limit($limit)->get();
    }

    /**
     * @param $table
     * @return array
     */
    protected function getAllTableFields($table)
    {
        $selectColumns = [];
        foreach (Schema::getColumnListing($table) as $column) {
            $selectColumns[] = "$table." . $column . " AS $table" . '_' . $column;
        }
        return $selectColumns;
    }

    /**
     * @param $table
     * @param $alias
     * @return array
     */
    protected function getAllTableFieldsByAlias($table, $alias)
    {
        $selectColumns = [];

        foreach (Schema::getColumnListing($table) as $column) {
            $selectColumns[] = "$alias." . $column . " AS $alias" . '__' . $column;
        }

        return $selectColumns;
    }
}
