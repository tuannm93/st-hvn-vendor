<?php

namespace App\Repositories\Eloquent;

use App\Models\MUser;
use App\Repositories\MUserRepositoryInterface;
use Illuminate\Support\Facades\DB;

class MUserRepository extends SingleKeyModelRepository implements MUserRepositoryInterface
{

    /**
     * @var MUser
     */
    public $mUser;

    /**
     * @var MUser
     */
    protected $model;

    /**
     * MUserRepository constructor.
     *
     * @param MUser $model
     */
    public function __construct(MUser $model)
    {
        $this->model = $model;
    }

    /**
     * @return \App\Models\Base|MUser|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new MUser();
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
     * @return \Illuminate\Support\Collection|mixed
     */
    public function dropDownUser()
    {
        $results = $this->model->where('auth', '!=', 'affiliation')
            ->orderBy('user_name', 'asc')
            ->pluck('user_name', 'id');

        return $results;
    }

    /**
     * @return array|mixed
     */
    public function getListUserNotAffiliation()
    {
        $listUser = $this->model->select(
            'MUser.id as id',
            'MUser.user_id as user_id',
            'MUser.user_name as user_name',
            'MUser.password as password',
            'MUser.user_type as user_type',
            'MUser.auth as auth',
            'MUser.affiliation_id as affiliation_id',
            'MUser.password_modify_date as password_modify_date',
            'MUser.modified_user_id as modified_user_id',
            'MUser.modified as modified',
            'MUser.created_user_id as created_user_id',
            'MUser.created as created',
            'MUser.last_login_date as last_login_date'
        )
            ->from('public.m_users as MUser')
            ->where('auth', '!=', 'affiliation')
            ->orderBy('user_name', 'ASC')
            ->get()->toArray();
        return $listUser;
    }

    /**
     * Description function: get user has column auth != affiliation
     *
     * @return array|mixed
     */
    public function getUser()
    {
        $list = $this->model->where('auth', '!=', 'affiliation')
            ->orderBy('user_name', 'asc')
            ->get();
        if (count($list) > 0) {
            $list = $list->mapWithKeys(function ($item) {
                return [$item['id'] => $item['user_name']];
            });
        }
        return $list->toArray();
    }

    /**
     * @param array $data
     * @return bool|mixed
     */
    public function saveUser($data)
    {
        try {
            DB::beginTransaction();
            if ($this->model->create($data)) {
                DB::commit();
                return true;
            } else {
                DB::rollBack();
                return false;
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * @author Dung.PhamVan@nashtechglobal.com
     * @return boolean|\Illuminate\Config\Repository|mixed
     */
    public function getListUserForDropDown()
    {
        return config('constant.defaultOption') + $this->model->getListUserForDropDown();
    }

    /**
     * Get list user for search page, if $pageNumber <= 0 then get all data
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param  integer $pageNumber
     * @param  string $auth
     * @param  string $username
     * @param  string $corpName
     * @return mixed
     */
    public function getUserForSearch($pageNumber = 100, $auth = null, $username = null, $corpName = null)
    {
        $query = $this->model->leftJoin("m_corps", "m_users.affiliation_id", "m_corps.id")
            ->where(
                function ($query) use ($auth, $username, $corpName) {
                    if ($auth) {
                        $query->where("m_users.auth", $auth);
                    }
                    if ($username) {
                        $query->whereRaw(DB::raw("z2h_kana(\"user_name\") like '%" . $username . "%'"));
                    }
                    if ($corpName) {
                        $query->whereRaw(DB::raw("z2h_kana(\"corp_name\") like '%" . $corpName . "%'"));
                    }
                }
            )->select("m_corps.*", "m_users.*", "m_corps.id as m_corps_id")->orderBy("m_users.id", "desc");
        if ($pageNumber > 0) {
            return $query->paginate($pageNumber);
        } else {
            return $query->get();
        }
    }


    /**
     * @return array|mixed
     */
    public function dropDownUserList()
    {
        return $this->model->where('auth', '!=', 'affiliation')->pluck('user_id', 'user_name')->toarray();
    }

    /**
     * @param integer $affId
     * @return \Illuminate\Support\Collection
     */
    public function getUserByAffiliationId($affId)
    {
        return $this->model->where('affiliation_id', $affId)->get();
    }

    /**
     * @param integer $userId
     * @return array|mixed
     */
    public function getUserById($userId)
    {
        return $this->model->where('id', $userId)->first()->toArray();
    }

    /**
     * @param integer $userId
     * @param array $data
     * @return bool|mixed
     */
    public function updateUser($userId, $data)
    {
        try {
            DB::beginTransaction();
            if ($this->model->where('id', $userId)->update($data)) {
                DB::commit();
                return true;
            } else {
                DB::rollBack();
                return false;
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * update last_login_date
     *
     * @param  string $userId
     * @param  array $data
     * @return mixed|void
     */
    public function updateLastLogin($userId, $data)
    {
        $this->model->where('user_id', $userId)->update($data);
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function getUserByUserId($userId)
    {
        return $this->model->where('user_id', $userId)->first();
    }

    /**
     * @param $data
     * @return mixed
     */
    public function getUserByUserIdAndPassword($data)
    {
        return $this->model->where('user_id', $data['user_id'])->first();
    }
}
