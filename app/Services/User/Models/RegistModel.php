<?php

namespace App\Services\User\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 指定可以注册的手机号的数据表
 *
 * Class RegistModel
 * @package App\Services\User\Models
 */
class RegistModel extends Model
{
    /**
     * 数据表名
     *
     * @var string
     */
    protected $table = 'regist';

    /**
     * 表明模型是否应该被打上时间戳
     *
     * 针对 create_at update_at 字段
     *
     * @var bool
     */
    public $timestamps = false;



}
