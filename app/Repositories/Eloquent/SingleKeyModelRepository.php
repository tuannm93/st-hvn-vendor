<?php

namespace App\Repositories\Eloquent;

use App\Repositories\SingleKeyModelRepositoryInterface;
use Illuminate\Support\Str;

class SingleKeyModelRepository extends BaseRepository implements SingleKeyModelRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getPrimaryKey()
    {
        $model = $this->getBlankModel();

        return $model->getKeyName();
    }

    /**
     * {@inheritdoc}
     */
    public function find($id, $columns = ['*'])
    {
        $modelClass = $this->getModelClassName();
        if ($this->cacheEnabled) {
            $key = $this->getCacheKey([$id]);
            \Log::info("Cache Check $key");
            $data = \Cache::remember(
                $key,
                $this->cacheLifeTime,
                function () use ($id, $modelClass, $columns) {
                    $modelClass = $this->getModelClassName();

                    return $modelClass::find($id, $columns);
                }
            );

            return $data;
        } else {
            return $modelClass::find($id, $columns);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function allByIds($ids, $order = null, $direction = null, $reorder = false)
    {
        if (count($ids) == 0) {
            return $this->getEmptyList();
        }
        $modelClass = $this->getModelClassName();
        $primaryKey = $this->getPrimaryKey();

        $query = $modelClass::whereIn($primaryKey, $ids);
        if (!empty($order)) {
            $direction = empty($direction) ? 'asc' : $direction;
            $query = $query->orderBy($order, $direction);
        }

        $models = $query->get();

        if (!$reorder) {
            return $models;
        }

        $result = $this->getEmptyList();
        $map = [];
        foreach ($models as $model) {
            $map[$model->id] = $model;
        }
        foreach ($ids as $id) {
            $model = $map[$id];
            if (!empty($model)) {
                $result->push($model);
            }
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function countByIds($ids)
    {
        if (count($ids) == 0) {
            return 0;
        }
        $modelClass = $this->getModelClassName();
        $primaryKey = $this->getPrimaryKey();

        return $modelClass::whereIn($primaryKey, $ids)->count();
    }

    /**
     * {@inheritdoc}
     */
    public function getByIds($ids, $order = null, $direction = null, $offset = null, $limit = null)
    {
        if (count($ids) == 0) {
            return $this->getEmptyList();
        }
        $modelClass = $this->getModelClassName();
        $primaryKey = $this->getPrimaryKey();

        $query = $modelClass::whereIn($primaryKey, $ids);
        if (!empty($order)) {
            $direction = empty($direction) ? 'asc' : $direction;
            $query = $query->orderBy($order, $direction);
        }
        if (!is_null($offset) && !is_null($limit)) {
            $query = $query->offset($offset)->limit($limit);
        }

        return $query->get();
    }

    /**
     * {@inheritdoc}
     */
    public function paginate($pageNumber = 5, $whereCondition = null)
    {
        $modelClass = $this->getModelClassName();
        if (isset($whereCondition)) {
            $model = $modelClass::where($whereCondition)->paginate($pageNumber);
        } else {
            $model = $modelClass::paginate($pageNumber);
        }

        return $model;
    }

    /**
     * {@inheritdoc}
     */
    public function create($input)
    {
        $model = $this->getBlankModel();

        return $this->update($model, $input);
    }

    /**
     * {@inheritdoc}
     */
    public function update($model, $input)
    {
        foreach ($model->getEditableColumns() as $column) {
            if (array_key_exists($column, $input)) {
                $model->$column = array_get($input, $column);
            }
        }

        if ($this->cacheEnabled) {
            $primaryKey = $this->getPrimaryKey();
            $key = $this->getCacheKey([$model->$primaryKey]);
            \Log::info("Cache Remove $key");
            \Cache::forget($key);
        }

        return $this->save($model);
    }

    /**
     * {@inheritdoc}
     */
    public function save($model)
    {
        if (!$model->save()) {
            return false;
        }

        return $model;
    }

    /**
     * {@inheritdoc}
     */
    public function delete($model)
    {
        return $model->delete();
    }

    /**
     * @param $method
     * @param $parameters
     * @return mixed
     * @throws \BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        if (Str::startsWith($method, 'getBy')) {
            return $this->dynamicGet($method, $parameters);
        }

        if (Str::startsWith($method, 'allBy')) {
            return $this->dynamicAll($method, $parameters);
        }

        if (Str::startsWith($method, 'countBy')) {
            return $this->dynamicCount($method, $parameters);
        }

        if (Str::startsWith($method, 'findBy')) {
            return $this->dynamicFind($method, $parameters);
        }

        if (Str::startsWith($method, 'deleteBy')) {
            return $this->dynamicDelete($method, $parameters);
        }

        $className = static::class;
        throw new \BadMethodCallException("Call to undefined method {$className}::{$method}()");
    }

    /**
     * @param $method
     * @param $parameters
     * @return mixed
     */
    private function dynamicGet($method, $parameters)
    {
        $finder = substr($method, 5);
        $segments = preg_split('/(And|Or)(?=[A-Z])/', $finder, -1);
        $conditionCount = count($segments);
        $conditionParams = array_splice($parameters, 0, $conditionCount);
        $model = $this->getBlankModel();
        $whereMethod = 'where' . $finder;
        $query = call_user_func_array([$model, $whereMethod], $conditionParams);

        $order = array_get($parameters, 0, 'id');
        $direction = array_get($parameters, 1, 'asc');
        $offset = array_get($parameters, 2, 0);
        $limit = array_get($parameters, 3, 10);

        if (!empty($order)) {
            $direction = empty($direction) ? 'asc' : $direction;
            $query = $query->orderBy($order, $direction);
        }
        if (!is_null($offset) && !is_null($limit)) {
            $query = $query->offset($offset)->limit($limit);
        }

        return $query->get();
    }

    /**
     * @param $method
     * @param $parameters
     * @return mixed
     */
    private function dynamicAll($method, $parameters)
    {
        $finder = substr($method, 5);
        $segments = preg_split('/(And|Or)(?=[A-Z])/', $finder, -1);
        $conditionCount = count($segments);
        $conditionParams = array_splice($parameters, 0, $conditionCount);
        $model = $this->getBlankModel();
        $whereMethod = 'where' . $finder;
        $query = call_user_func_array([$model, $whereMethod], $conditionParams);

        $order = array_get($parameters, 0, 'id');
        $direction = array_get($parameters, 1, 'asc');

        return $query->orderBy($order, $direction)->get();
    }

    /**
     * @param $method
     * @param $parameters
     * @return mixed
     */
    private function dynamicCount($method, $parameters)
    {
        $finder = substr($method, 7);
        $segments = preg_split('/(And|Or)(?=[A-Z])/', $finder, -1);
        $conditionCount = count($segments);
        $conditionParams = array_splice($parameters, 0, $conditionCount);
        $model = $this->getBlankModel();
        $whereMethod = 'where' . $finder;
        $query = call_user_func_array([$model, $whereMethod], $conditionParams);

        return $query->count();
    }

    /**
     * @param $method
     * @param $parameters
     * @return mixed
     */
    private function dynamicFind($method, $parameters)
    {
        $finder = substr($method, 6);
        $segments = preg_split('/(And|Or)(?=[A-Z])/', $finder, -1);
        $conditionCount = count($segments);
        $conditionParams = array_splice($parameters, 0, $conditionCount);
        $model = $this->getBlankModel();
        $whereMethod = 'where' . $finder;
        $query = call_user_func_array([$model, $whereMethod], $conditionParams);

        return $query->first();
    }

    /**
     * @param $method
     * @param $parameters
     * @return mixed
     */
    private function dynamicDelete($method, $parameters)
    {
        $finder = substr($method, 8);
        $segments = preg_split('/(And|Or)(?=[A-Z])/', $finder, -1);
        $conditionCount = count($segments);
        $conditionParams = array_splice($parameters, 0, $conditionCount);
        $model = $this->getBlankModel();
        $whereMethod = 'where' . $finder;
        $query = call_user_func_array([$model, $whereMethod], $conditionParams);

        return $query->delete();
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function insertOrUpdateItem($input, $item = null)
    {
        $result['status'] = true;
        if (!$item) {
            $item = $this->getBlankModel();
        }
        $item->fill($input);
        if ($item->save()) {
            $result['item'] = $item;
            return $result;
        }
        $result['status'] = false;
        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteListItemByArrayId($arrayId)
    {
        return $this->getModelClassName()::whereIn('id', $arrayId)->delete();
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($id)
    {
        return $this->getModelClassName()::find($id)->delete();
    }

    /**
     * {@inheritdoc}
     */
    public function findLastItem()
    {
        return $this->getModelClassName()::orderBy('id', 'desc')->first();
    }

    /**
     * insert item or many item
     *
     * @param  array $data
     * @return boolean
     */
    public function insert($data)
    {
        return $this->getModelClassName()::insert($data);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function insertGetObject($data)
    {
        return $this->getModelClassName()::create($data);
    }

    /**
     * {@inheritdoc}
     */
    public function deleteByCondition($condition)
    {
        return $this->getModelClassName()::where($condition)->delete();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findOrFail($id)
    {
        return $this->getModelClassName()::findOrFail($id);
    }

    /**
     * {@inheritdoc}
     */
    public function insertGetId($data)
    {
        return $this->getModelClassName()::insertGetId($data);
    }

    /**
     * Insert or update multi data
     * @param $data
     * @return mixed|string
     */
    public function insertOrUpdateMultiData($data)
    {
        $result = [];
        foreach ($data as $value) {
            if (isset($value['id'])) {
                $id = $value['id'];
                unset($value['id']);
                $result = $this->getModelClassName()::where('id', $id)->update($value);
                if (!$result) {
                    break;
                }
            } else {
                $result = $this->getModelClassName()::insert($value);
                if (!$result) {
                    break;
                }
            }
        }

        return $result;
    }
}
