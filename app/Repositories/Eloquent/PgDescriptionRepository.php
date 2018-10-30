<?php

namespace App\Repositories\Eloquent;

use App\Repositories\PgDescriptionRepositoryInterface;
use App\Models\PgDescription;

class PgDescriptionRepository extends SingleKeyModelRepository implements PgDescriptionRepositoryInterface
{
    /**
     * @var PgDescription
     */
    protected $model;

    /**
     * PgDescriptionRepository constructor.
     * @param PgDescription $model
     */
    public function __construct(PgDescription $model)
    {
        $this->model = $model;
    }
    /**
     * @param string $tableName
     * @param string $columns
     * @return mixed
     */
    public function getColumnAlias($tableName, $columns)
    {
        $result = $this->model
            ->select('psat.relname as table_name', 'pa.attname as column_name', 'pg_description.description as column_comment')
            ->join('pg_stat_all_tables AS psat', 'psat.relid', '=', 'pg_description.objoid')
            ->join('pg_attribute AS pa', function ($join) {
                $join->on('pg_description.objoid', '=', 'pa.attrelid');
                $join->on('pg_description.objsubid', '=', 'pa.attnum');
            })
            ->where('psat.relname', '=', $tableName)
            ->where('pg_description.objsubid', '!=', 0)
            ->whereIn('pa.attname', $columns)
            ->orderBy('pg_description.objsubid', 'asc')->get()->toarray();
        return $result;
    }
}
