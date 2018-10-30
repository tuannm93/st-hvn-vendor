<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MInquiry
 *
 * @property int $id ヒアリング項目ID
 * @property string|null $inquiry_name ヒアリング項目名
 * @property int $category_id カテゴリID
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MInquiry whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MInquiry whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MInquiry whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MInquiry whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MInquiry whereInquiryName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MInquiry whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MInquiry whereModifiedUserId($value)
 * @mixin \Eloquent
 */
class MInquiry extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_inquiries';
    /**
     * @var boolean
     */
    public $timestamps = false;
}
