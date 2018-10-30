<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\DemandInquiryAns
 *
 * @property int $id ID
 * @property int $demand_id 案件ID
 * @property int $inquiry_id ヒアリング項目ID
 * @property string|null $answer_note 回答内容
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @property-read \App\Models\MInquiry $mInquiry
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInquiryAns whereAnswerNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInquiryAns whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInquiryAns whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInquiryAns whereDemandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInquiryAns whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInquiryAns whereInquiryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInquiryAns whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInquiryAns whereModifiedUserId($value)
 * @mixin \Eloquent
 */
class DemandInquiryAns extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'demand_inquiry_answers';
    /**
     * @var boolean
     */
    public $timestamps = false;
    /**
     * @var array
     */
    protected $guarded = ['id'];
    const UPDATED_AT = 'modified';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mInquiry()
    {
        return $this->belongsTo('App\Models\MInquiry', 'inquiry_id', 'id');
    }
}
