<?php

namespace App\Services\User\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 用户省流量 年 表
 *
 * Class AccountSaveFlowYearModel
 * @package App\Services\User\Models
 */
class AccountSaveFlowYearModel extends Model
{
    /**
     * 数据表名
     *
     * @var string
     */
    protected $table = 'account_save_flow_year';

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
