<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MAnswer
 *
 * @property int $id 回答項目ID
 * @property string|null $answer_name 回答項目名
 * @property int $inquiry_id ヒアリング項目ID
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MAnswer whereAnswerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MAnswer whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MAnswer whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MAnswer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MAnswer whereInquiryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MAnswer whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MAnswer whereModifiedUserId($value)
 * @mixin \Eloquent
 */
class MAnswer extends Model
{
    /**
     * @var string
     */
    protected $table = 'm_answers';
}
