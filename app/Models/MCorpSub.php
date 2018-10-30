<?php

namespace App\Models;

/**
 * App\Models\MCorpSub
 *
 * @property int $id ID
 * @property int $corp_id 企業ID
 * @property string $item_category 項目カテゴリ
 * @property int $item_id 項目ID
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpSub whereCorpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpSub whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpSub whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpSub whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpSub whereItemCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpSub whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpSub whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpSub whereModifiedUserId($value)
 * @mixin \Eloquent
 */
class MCorpSub extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_corp_subs';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;

    /**
     *
     */
    const HOLIDAY = '休業日';
}
