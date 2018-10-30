<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProgItem
 *
 * @property int $id ID
 * @property string|null $up_text 上部文言
 * @property string|null $down_text 下部文言
 * @property int|null $return_limit 返送期限
 * @property string|null $created 作成日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $modified 更新日時
 * @property string|null $modified_user_id 更新者ID
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgItem whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgItem whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgItem whereDownText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgItem whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgItem whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgItem whereReturnLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgItem whereUpText($value)
 * @mixin \Eloquent
 */
class ProgItem extends Model
{
    const EDIT_DATE = 6;
    /**
     * @var string
     */
    protected $table = 'prog_items';

    /**
     * @var boolean
     */
    public $timestamps = false;

    /**
     * @return array
     */
    public function getEditableColumns()
    {
        return [
            'up_text',
            'down_text',
            'return_limit',
            'modified',
            'modified_user_id',
        ];
    }
}
