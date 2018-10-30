<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CorpLicenseLink
 *
 * @property int $id ID
 * @property int $corps_id corps_id
 * @property int $lisense_id lisense_id
 * @property bool $have_lisense 保持フラグ
 * @property int $version_no バージョンNo
 * @property string|null $create_date 登録日
 * @property string|null $create_user_id 登録ユーザーID
 * @property string|null $update_date 更新日
 * @property string|null $update_user_id 更新ユーザーID
 * @property string|null $delete_date 削除日
 * @property bool $delete_flag 削除フラグ
 * @property string $lisense_check 資格確認　
 * None：未確認
 * OK：確認済み
 * NG：書類不備
 * @property string|null $license_expiration_date ライセンス期限日
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpLicenseLink whereCorpsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpLicenseLink whereCreateDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpLicenseLink whereCreateUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpLicenseLink whereDeleteDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpLicenseLink whereDeleteFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpLicenseLink whereHaveLisense($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpLicenseLink whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpLicenseLink whereLicenseExpirationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpLicenseLink whereLisenseCheck($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpLicenseLink whereLisenseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpLicenseLink whereUpdateDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpLicenseLink whereUpdateUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpLicenseLink whereVersionNo($value)
 * @mixin \Eloquent
 */
class CorpLicenseLink extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'corp_lisense_link';

    /**
     * @var boolean
     */
    public $timestamps = false;
}
