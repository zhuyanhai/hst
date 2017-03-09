<?php

namespace App\Services\Shop\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 商户数据表
 *
 * Class ShopModel
 * @package App\Services\Shop\Models
 */
class ShopModel extends Model
{
    /**
     * 数据表名
     *
     * @var string
     */
    protected $table = 'shop';

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
