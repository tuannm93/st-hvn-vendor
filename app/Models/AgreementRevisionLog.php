<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AgreementRevisionLog
 *
 * @property int $id ID
 * @property string $content 契約約款全文
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementRevisionLog whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementRevisionLog whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementRevisionLog whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementRevisionLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementRevisionLog whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementRevisionLog whereModifiedUserId($value)
 * @mixin \Eloquent
 */
class AgreementRevisionLog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'agreement_revision_logs';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;
}
