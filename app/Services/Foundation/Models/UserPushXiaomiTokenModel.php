<?php

namespace App\Services\Foundation\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * app设备推送token记录表(小米推送)
 *
 * Class UserPushXiaomiTokenModel
 * @package App\Services\Foundation\Models
 */
class UserPushXiaomiTokenModel extends Model
{
    /**
     * 数据表名
     *
     * @var string
     */
    protected $table = 'user_push_xiaomitoken';

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
