<?php

namespace App\Repositories\Eloquent;

use App\Repositories\AntisocialCheckRepositoryInterface;
use App\Models\AntisocialCheck;
use DB;
use Auth;

class AntisocialCheckRepository extends SingleKeyModelRepository implements AntisocialCheckRepositoryInterface
{
    /**
     * @var AntisocialCheck
     */
    protected $model;

    /**
     * AntisocialCheckRepository constructor.
     *
     * @param AntisocialCheck $model
     */
    public function __construct(AntisocialCheck $model)
    {
        $this->model = $model;
    }

    /**
     * @return AntisocialCheck|\App\Models\Base|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new AntisocialCheck();
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
     * find history by corp id
     *
     * @param  integer $corpId
     * @param  string  $type
     * @return array object antisocial checks
     */
    public function findHistoryByCorpId($corpId, $type = 'first')
    {
        $result = '';
        $query = $this->model
            ->select('date')
            ->selectRaw(DB::raw("COALESCE(m_users.user_name, antisocial_checks.created_user_id) AS created_user"))
            ->leftJoin('m_users', 'm_users.user_id', '=', 'antisocial_checks.created_user_id')
            ->where('corp_id', $corpId)
            ->orderBy('date', 'desc');

        if ($type == 'first') {
            $result = $query->first();
        } elseif ($type == 'all') {
            $result = $query->get();
        }

        return $result;
    }

    /**
     * get result list
     *
     * @return array
     */
    public function getResultList()
    {
        return $this->model->getResultList();
    }

    /**
     * get options
     *
     * @return object query
     */
    public function getOptions()
    {
        return $this->model->select(
            'MCorp.antisocial_check_month',
            'MCorp.last_antisocial_check_date',
            'MCorp.id AS mcorp_id',
            'MCorp.official_corp_name',
            'MCorp.commission_dial'
        )
            ->from('public.antisocial_checks as AntisocialCheck')
            ->addSelect(DB::raw('( MAX("AntisocialCheck".date) )'))
            ->addSelect(DB::raw('( concat( "MCorp".antisocial_check_month, ' . "'月'" . '))'))
            ->rightJoin(
                'public.m_corps as MCorp',
                function ($join) {
                    $join->on('MCorp.id', '=', 'AntisocialCheck.corp_id');
                }
            )
            ->whereNotIn('MCorp.id', [1751, 1755, 3539])
            ->where('MCorp.affiliation_status', '=', 1)
            ->whereRaw('NOT (COALESCE("MCorp"."corp_commission_status",0) = 2)')
            ->where('MCorp.del_flg', '=', 0)
            ->whereRaw('"MCorp"."last_antisocial_check"=' . "'OK'")
            ->whereRaw(
                '( ( now() >= to_timestamp( ( date_part(' . "'year'" . ', "AntisocialCheck"."date")+ 
            CASE WHEN date_part(' . "'month'" . ', "AntisocialCheck"."date") < "MCorp"."antisocial_check_month" 
            THEN 0 ELSE 1 END ) || ' . "'/'" . ' || "MCorp"."antisocial_check_month" || 
            ' . "'/1'" . ', ' . "'YYYY/MM/DD'" . ' ) ) OR ("AntisocialCheck"."id" IS NULL) )'
            )
            ->whereRaw(
                'NOT EXISTS ( SELECT 1 FROM antisocial_checks WHERE "antisocial_checks"."corp_id" = 
            "AntisocialCheck"."corp_id" AND "antisocial_checks"."created" > "AntisocialCheck"."created")',
                [],
                'AND'
            )
            ->groupBy('MCorp.id')
            ->orderByRaw('( ( MAX("AntisocialCheck".date) Is NULL ) ) DESC')
            ->orderByRaw('( MAX("AntisocialCheck".date) ) ASC')
            ->orderByRaw('("MCorp".id) ASC');
    }

    /**
     * get list antisocial
     *
     * @return array
     */
    public function getAntisocialList()
    {
        $query = $this->getOptions();
        $result = $query->limit(\Config::get('datacustom.limit'))->paginate(100);
        return $result;
    }

    /**
     * check author
     *
     * @param  string $auth
     * @return boolean
     */
    public function isUpdateAuthority($auth)
    {
        return in_array($auth, ['system', 'admin', 'accounting_admin']);
    }

    /**
     * get data for export csv
     *
     * @return array
     */
    public function getDataCsv()
    {
        $query = $this->getOptions()->addSelect('MCorp.corp_name_kana');
        $result = $query->get()->toarray();
        return $result;
    }

    /**
     * update data table
     *
     * @param  array  $data
     * @param  string $auth
     * @return boolean
     */
    public function updateDataAntisocialFollow($data, $auth)
    {
        if (!$this->isUpdateAuthority($auth)) {
            abort('404');
        }
        if (!isset($data['check'])) {
            return false;
        }
        $saveData = [];
        foreach ($data['check'] as $corpId) {
            $saveData[] = [
                'corp_id' => $corpId,
                'date' => date('Y-m-d H:i:s'),
                'created_user_id' => Auth::user()['user_id'],
                'modified_user_id' => Auth::user()['user_id'],
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ];
        }
        $result = true;
        if (!empty($saveData)) {
            DB::table('antisocial_checks')->insert($saveData);
        }
        return $result;
    }

    /**
     * Return the list of anti-company check month
     *
     * @return array
     */
    public function getMonthList()
    {
        $months = [
            1 => '1月',
            2 => '2月',
            3 => '3月',
            4 => '4月',
            5 => '5月',
            6 => '6月',
            7 => '7月',
            8 => '8月',
            9 => '9月',
            10 => '10月',
            11 => '11月',
            12 => '12月',
        ];
        return $months;
    }

    /**
     * @param integer $corpId
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getAntisocialFollow($corpId)
    {
        $query = $this->model
            ->select('m_corps.id AS m_corps_id')
            ->from('antisocial_checks AS AntisocialCheck')
            ->rightJoin(
                'm_corps',
                'm_corps.id',
                '=',
                'AntisocialCheck.corp_id'
            )->where('m_corps.id', '=', $corpId)
            ->where('m_corps.antisocial_display_flag', '=', 1)
            ->where('m_corps.affiliation_status', '=', 1)
            ->where('m_corps.last_antisocial_check', '=', 'OK')
            ->whereRaw("now() >= to_timestamp((date_part('year', \"AntisocialCheck\".date)+CASE WHEN date_part('month', \"AntisocialCheck\".date) < m_corps.antisocial_check_month THEN 0 ELSE 1 END) || '/' || m_corps.antisocial_check_month || '/1','YYYY/MM/DD')")
            ->whereRaw("NOT EXISTS( SELECT 1 FROM antisocial_checks WHERE antisocial_checks.corp_id = \"AntisocialCheck\".corp_id AND antisocial_checks.created > \"AntisocialCheck\".created )")
            ->where('m_corps.del_flg', '=', 0)
            ->orderBy('m_corps.id');
        return $query->first();
    }
}
