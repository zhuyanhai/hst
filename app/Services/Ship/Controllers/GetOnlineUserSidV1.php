<?php

namespace App\Services\Foundation\Controllers;

use App\Services\ServiceAbstract;
use App\Services\User\Models\RelationModel;

/**
 * 获取在线用户的船id
 *
 * 版本号：v1
 *
 * Class GetOnlineUserSidV1
 * @package App\Services\Foundation\Controllers
 */
class GetOnlineUserSidV1 extends ServiceAbstract
{
    /**
     * 校验请求参数
     *
     * true = 校验通过 false=校验不通过
     * @return boolean
     */
    public function paramsValidate()
    {
        return $this->_validate($this->_params, [
            'userid'  => 'required|integer',
        ], [
            'userid.required' => '参数userid丢失',
            'userid.integer'  => '参数userid丢失'
        ]);
    }

    /**
     * 服务必须实现的方法，因为调用服务会自动调用本方法
     *
     * @return array
     */
    public function run()
    {
        $list = RelationModel::where('uid', $this->_params['userid'])->where('online', 1)->get(['sid']);
        return $this->response($list->toArray());
    }
}