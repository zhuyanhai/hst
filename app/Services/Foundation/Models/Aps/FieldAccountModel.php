<?php

namespace App\Services\Foundation\Models\Aps;

use Illuminate\Database\Eloquent\Model;

/**
 * voip账户数据表
 *
 * Class Aps_FieldAccountModel
 * @package App\Services\Foundation\Models
 */
class Aps_FieldAccountModel extends Model
{
    /**
     * 数据表名
     *
     * @var string
     */
    protected $table = 'field_account';

    /**
     * 数据表 - 主键字段名
     *
     * @var string
     */
    protected $primaryKey = 'field_account_id';

    /**
     * 表明模型是否应该被打上时间戳
     *
     * 针对 created_at updated_at 字段
     *
     * @var bool
     */
    public $timestamps = false;



}
