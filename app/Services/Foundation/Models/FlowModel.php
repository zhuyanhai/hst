<?php

namespace App\Services\Foundation\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 用户手机验证码数据表
 *
 * Class FlowModel
 * @package App\Services\Foundation\Models
 */
class FlowModel extends Model
{
    /**
     * 数据表名
     *
     * @var string
     */
    protected $table = 'flow';

    /**
     * 数据表 - 主键字段名
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * 表明模型是否应该被打上时间戳
     *
     * 针对 create_at update_at 字段
     *
     * @var bool
     */
    public $timestamps = false;



}
