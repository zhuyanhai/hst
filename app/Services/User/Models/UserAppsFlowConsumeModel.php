<?php

namespace App\Services\User\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 用户每日手机上各app消耗流量的日志数据表
 *
 * Class UserReportedAppsFlowModel
 * @package App\Services\User\Models
 */
class UserAppsFlowConsumeModel extends Model
{
    /**
     * 数据表名
     *
     * @var string
     */
    protected $table = 'user_apps_flow_consume';

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

    /**
     * 可以被批量赋值的属性.
     *
     * @var array
     */
    protected $fillable = ['userid', 'dates', 'contents'];

}
