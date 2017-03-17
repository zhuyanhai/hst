<?php

namespace App\Services\Foundation\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 用户手机验证码数据表
 *
 * Class CodeModel
 * @package App\Services\Foundation\Models
 */
class CodeModel extends Model
{
    /**
     * 数据表名
     *
     * @var string
     */
    protected $table = 'user_code';

    /**
     * 数据表 - 主键字段名
     *
     * @var string
     */
    protected $primaryKey = 'phone';

    /**
     * 表明模型是否应该被打上时间戳
     *
     * 针对 created_at updated_at 字段
     *
     * @var bool
     */
    public $timestamps = false;



}
