<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CorpAgreementTempLink
 *
 * @property int $id ID
 * @property int $corp_id 企業ID
 * @property int|null $corp_agreement_id 契約ID
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @property-read \App\Models\CorpAgreement|null $corpAgreement
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MCorpCategoriesTemp[] $mCorpCategoriesTemps
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpAgreementTempLink whereCorpAgreementId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpAgreementTempLink whereCorpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpAgreementTempLink whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpAgreementTempLink whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpAgreementTempLink whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpAgreementTempLink whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpAgreementTempLink whereModifiedUserId($value)
 * @mixin \Eloquent
 */
class CorpAgreementTempLink extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'corp_agreement_temp_link';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function corpAgreement()
    {
        return $this->belongsTo('App\Models\CorpAgreement', 'corp_agreement_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function mCorpCategoriesTemps()
    {
        return $this->hasMany('App\Models\MCorpCategoriesTemp', 'temp_id', 'id');
    }
}
