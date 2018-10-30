<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AffiliationCorrespond
 *
 * @property int $id ID
 * @property int $corp_id 企業ID
 * @property string|null $correspond_datetime 対応日時
 * @property string|null $responders 対応者
 * @property string|null $corresponding_contens 対応内容
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationCorrespond whereCorpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationCorrespond whereCorrespondDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationCorrespond whereCorrespondingContens($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationCorrespond whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationCorrespond whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationCorrespond whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationCorrespond whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationCorrespond whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationCorrespond whereResponders($value)
 * @mixin \Eloquent
 */
class AffiliationCorrespond extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'affiliation_corresponds';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;
}
