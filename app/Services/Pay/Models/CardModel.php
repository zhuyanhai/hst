<?php

namespace App\Services\Pay\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 充值卡记录表数据表
 *
 * Class CardModel
 * @package App\Services\Pay\Models
 */
class CardModel extends Model
{
    /**
     * 数据表名
     *
     * @var string
     */
    protected $table = 'card';

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
