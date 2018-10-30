<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User;

/**
 * App\Models\MUser
 *
 * @property int $id ID
 * @property string $user_id ユーザーID
 * @property string|null $user_name ユーザー名
 * @property string $password パスワード
 * @property int|null $user_type ユーザー種別
 * @property string|null $auth 権限
 * @property int|null $affiliation_id 加盟店ID
 * @property string|null $password_modify_date 最終パスワード変更日時
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @property string|null $cookie_id
 * @property string|null $last_login_date 最終ログイン日時
 * @property string|null $remember_token
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MUser whereAffiliationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MUser whereAuth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MUser whereCookieId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MUser whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MUser whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MUser whereLastLoginDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MUser whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MUser whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MUser wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MUser wherePasswordModifyDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MUser whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MUser whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MUser whereUserName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MUser whereUserType($value)
 * @mixin \Eloquent
 */
class MUser extends User
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @var boolean
     */
    public $timestamps = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @param $roles
     * @return bool
     */
    public function hasRole($roles)
    {
        $auth = $this->auth;
        if (in_array($auth, $roles)) {
            return true;
        }

        return false;
    }

    /**
     * Description function: get user has column auth != affiliation
     *
     * @return array
     */
    public function dropDownUser()
    {
        $list = MUser::where('auth', '!=', 'affiliation')
            ->orderBy('user_name', 'asc')->get()->toarray();
        $results = [];
        foreach ($list as $val) {
            $results[$val['id']] = $val['user_name'];
        }
        return $results;
    }

    /**
     * @return bool
     */
    public function isPoster()
    {
        return in_array($this->auth, ['system', 'admin', 'accounting_admin']);
    }

    /**
     * @return bool
     */
    public function isReader()
    {
        return $this->auth == 'affiliation';
    }


    /**
     * @auth Dung.PhamVan@nashtechglobal.com
     */
    public function getListUserForDropDown()
    {
        return $this->where('auth', '!=', 'affiliation')
            ->select('user_name', 'id')
            ->orderBy('user_name', 'asc')
            ->pluck('user_name', 'id')->toArray();
    }

    /**
     * @return bool
     */
    public function isSystem()
    {
        return $this->auth == 'system';
    }

    /**
     * @return array
     */
    public static function csvFormat()
    {
        return [
            'user_name' => trans('user_search.user_name'),
            'corp_id' => trans('user_search.corp_id'),
            'official_corp_name' => trans('user_search.official_corp_name'),
            'auth' => trans('user_search.auth'),
            'last_login_date' => trans('user_search.last_login_date'),
        ];
    }
}
