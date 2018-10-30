<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AgreementProvisionsEditLog
 *
 * @property int $id ID
 * @property int $agreement_provisions_id 契約条文項目マスタID
 * @property int $agreement_revision_logs_id 契約約款改訂履歴ID
 * @property string $content 条文
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementProvisionsEditLog whereAgreementProvisionsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementProvisionsEditLog whereAgreementRevisionLogsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementProvisionsEditLog whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementProvisionsEditLog whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementProvisionsEditLog whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementProvisionsEditLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementProvisionsEditLog whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementProvisionsEditLog whereModifiedUserId($value)
 * @mixin \Eloquent
 */
class AgreementProvisionsEditLog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'agreement_provisions_edit_logs';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;
}
