<?php

namespace App\Repositories\Eloquent;

use App\Models\AgreementRevisionLog;
use App\Repositories\AgreementRevisionLogRepositoryInterface;
use App\Helpers\Util;
use Illuminate\Support\Facades\DB;

class AgreementRevisionLogRepository extends SingleKeyModelRepository implements AgreementRevisionLogRepositoryInterface
{
    /**
     * @var AgreementRevisionLog
     */
    protected $model;

    /**
     * AgreementRevisionLogRepository constructor.
     *
     * @param AgreementRevisionLog $model
     */
    public function __construct(AgreementRevisionLog $model)
    {
        $this->model = $model;
    }

    /**
     * @param integer $id
     * @return \Illuminate\Support\Collection
     */
    public function findByIdJoinWithMUser($id)
    {
        return DB::table('agreement_revision_logs as arl')
            ->select(
                'arl.id',
                'arl.content',
                'u.user_name',
                DB::raw('to_char(arl.created, \'YYYY/MM/DD HH24:MI\') as created_date')
            )
            ->join(
                'm_users as u',
                function ($join) {
                    $join->on(DB::raw('arl.created_user_id::int'), '=', 'u.id');
                }
            )
            ->where('arl.id', $id)->get();
    }

    /**
     * @return $this
     */
    public function getAllContractTermsRevisionHistoryJoinMUserWithoutContent()
    {
        return DB::table('agreement_revision_logs as arl')
            ->select(
                'arl.id',
                'u.user_name',
                DB::raw('to_char(arl.created, \'YYYY/MM/DD HH24:MI\') as created_date')
            )
            ->join(
                'm_users as u',
                function ($join) {
                    $join->on(DB::raw('arl.created_user_id::int'), '=', 'u.id');
                }
            )
            ->groupBy(
                [
                'arl.id', 'u.user_name'
                ]
            );
    }

    /**
     * @param array $data
     * @return bool|void
     */
    public function insert($data)
    {
        $this->model->insert($data);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getFirstAgreementRevisionLog()
    {
        return $this->model->orderBy('id', 'desc')->first();
    }

    /**
     * @return int|mixed
     */
    public function getMaxAgreementRevisionLogId()
    {
        $item = $this->getFirstAgreementRevisionLog();
        if (is_null($item)) {
            return 0;
        } else {
            return $item->id;
        }
    }
}
