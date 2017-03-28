<?php

namespace App\Services\User\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * sso_ticket 用户登陆票据记录表 - log
 */
class SsoTicketLogModel extends Model
{
    /**
     * 数据表名
     *
     * @var string
     */
    protected $table = 'sso_ticket_log';

    /**
     * 表明模型是否应该被打上时间戳
     *
     * 针对 created_at updated_at 字段
     *
     * @var bool
     */
    public $timestamps = false;
}