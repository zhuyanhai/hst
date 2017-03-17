<?php

namespace App\Services\User\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 用户注册后审核数据表
 *
 * Class UserRegisterCheckModel
 * @package App\Services\User\Models
 */
class UserRegisterCheckModel extends Model
{
    /**
     * 数据表名
     *
     * @var string
     */
    protected $table = 'user_register_check';

    /**
     * 数据表 - 主键字段名
     *
     * @var string
     */
    protected $primaryKey = 'uid';

    /**
     * 表明模型是否应该被打上时间戳
     *
     * 针对 created_at updated_at 字段
     *
     * @var bool
     */
    public $timestamps = false;



}
