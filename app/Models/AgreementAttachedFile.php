<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AgreementAttachedFile
 *
 * @property int $id ID
 * @property int $corp_id 企業ID
 * @property int|null $license_id ライセンスID
 * @property int $corp_agreement_id 会社毎の契約ID
 * @property int|null $m_corp_categories_id 企業別対応カテゴリマスタID
 * @property int|null $m_corp_categories_temp_id 企業別対応カテゴリマスタID
 * @property string $kind 種別     身分証明書、登記謄本：Cert
 * 許可証（ライセンス）：License
 * @property string|null $path ファイルパス
 * @property string|null $name ファイル名
 * @property string|null $content_type コンテントタイプ
 * @property bool|null $temp_flag 一時フラグ
 * @property int $version_no バージョンNo
 * @property string|null $create_date 登録日
 * @property string|null $create_user_id 登録ユーザーID
 * @property string|null $update_date 更新日
 * @property string|null $update_user_id 更新ユーザーID
 * @property string|null $delete_date 削除日
 * @property bool $delete_flag 削除フラグ
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementAttachedFile whereContentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementAttachedFile whereCorpAgreementId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementAttachedFile whereCorpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementAttachedFile whereCreateDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementAttachedFile whereCreateUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementAttachedFile whereDeleteDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementAttachedFile whereDeleteFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementAttachedFile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementAttachedFile whereKind($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementAttachedFile whereLicenseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementAttachedFile whereMCorpCategoriesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementAttachedFile whereMCorpCategoriesTempId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementAttachedFile whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementAttachedFile wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementAttachedFile whereTempFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementAttachedFile whereUpdateDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementAttachedFile whereUpdateUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementAttachedFile whereVersionNo($value)
 * @mixin \Eloquent
 */
class AgreementAttachedFile extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'agreement_attached_file';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;

    const CERT = 'Cert';
    const LICENSE = 'License';

    const KIND_CERT = [self::CERT => '身分証明書、登記謄本'];
    const KIND_LICENSE = [self::LICENSE => 'ライセンス'];

    /**
     * @return bool
     */
    public function isFileTypePdf()
    {
        if ($this->content_type != null && strpos($this->content_type, 'pdf')) {
            return true;
        }
        return false;
    }
}
