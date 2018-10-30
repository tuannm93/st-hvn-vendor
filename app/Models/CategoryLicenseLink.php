<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CategoryLicenseLink
 *
 * @property int $id id
 * @property int|null $genre_id ジャンルID
 * @property int|null $category_id カテゴリID
 * @property int|null $license_id ライセンスID
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @property int $version_no バージョンNo
 * @property string|null $create_date 登録日
 * @property string|null $create_user_id 登録ユーザーID
 * @property string|null $update_date 更新日
 * @property string|null $update_user_id 更新ユーザーID
 * @property string|null $delete_date 削除日
 * @property bool $delete_flag 削除フラグ
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CategoryLicenseLink whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CategoryLicenseLink whereCreateDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CategoryLicenseLink whereCreateUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CategoryLicenseLink whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CategoryLicenseLink whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CategoryLicenseLink whereDeleteDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CategoryLicenseLink whereDeleteFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CategoryLicenseLink whereGenreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CategoryLicenseLink whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CategoryLicenseLink whereLicenseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CategoryLicenseLink whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CategoryLicenseLink whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CategoryLicenseLink whereUpdateDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CategoryLicenseLink whereUpdateUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CategoryLicenseLink whereVersionNo($value)
 * @mixin \Eloquent
 */
class CategoryLicenseLink extends Model
{

    /**
     * @var string
     */
    protected $table = 'category_license_link';

    /**
     * @var boolean
     */
    public $timestamps = false;
}
