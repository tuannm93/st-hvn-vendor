<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MPost
 *
 * @property int $id ID
 * @property string $jis_cd 市町村コード
 * @property string $post_cd_old 旧郵便番号
 * @property string $post_cd 郵便番号
 * @property string $addr1_kana 都道府県名カナ
 * @property string $addr2_kana 市区町村名カナ
 * @property string $addr3_kana 町域名カナ
 * @property string $address1 都道府県名
 * @property string $address2 市区町村名
 * @property string $address3 町域名
 * @property string $gaitou_chk1 郵便番号重複  0：該当せず 1：該当
 * @property string $gaitou_chk2 要小字     0：該当せず 1：該当
 * @property string $gaitou_chk3 要丁目     0：該当せず 1：該当
 * @property string $gaitou_chk4 町域重複    0：該当せず 1：該当
 * @property string $upd_kbn 更新区分    0：変更なし 1：有 2：廃止
 * @property string $henko_riyuu 変更理由
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MPost whereAddr1Kana($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MPost whereAddr2Kana($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MPost whereAddr3Kana($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MPost whereAddress1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MPost whereAddress2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MPost whereAddress3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MPost whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MPost whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MPost whereGaitouChk1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MPost whereGaitouChk2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MPost whereGaitouChk3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MPost whereGaitouChk4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MPost whereHenkoRiyuu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MPost whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MPost whereJisCd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MPost whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MPost whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MPost wherePostCd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MPost wherePostCdOld($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MPost whereUpdKbn($value)
 * @mixin \Eloquent
 */
class MPost extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_posts';

    /**
     * @var array
     */
    protected $fillable = ['*'];

    /**
     * @var boolean
     */
    public $timestamps = false;
}
