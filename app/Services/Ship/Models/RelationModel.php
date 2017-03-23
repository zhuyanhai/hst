<?php

namespace App\Services\User\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 船与用户的关系数据表
 *
 * 记录用户在哪艘船上
 *
 * Class RelationModel
 * @package App\Services\User\Models
 */
class RelationModel extends Model
{
    /**
     * 数据表名
     *
     * @var string
     */
    protected $table = 'relation';

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
