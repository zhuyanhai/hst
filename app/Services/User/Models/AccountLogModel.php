<?php

namespace App\Services\User\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 帐号充值日志数据表
 *
 * Class AccountLogModel
 * @package App\Services\User\Models
 */
class AccountLogModel extends Model
{
    /**
     * 数据表名
     *
     * @var string
     */
    protected $table = 'account_log';

    /**
     * 数据表 - 主键字段名
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * 表明模型是否应该被打上时间戳
     *
     * 针对 created_at updated_at 字段
     *
     * @var bool
     */
    public $timestamps = false;



}
