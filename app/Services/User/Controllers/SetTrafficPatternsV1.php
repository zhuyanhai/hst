<?php

namespace App\Services\User\Controllers;

use App\Services\ServiceAbstract;
use App\Services\User\Models\UserModel;

/**
 * 设置用户省流量模式
 *
 * 版本号：v1
 *
 * Class SetTrafficPatternsV1
 * @package App\Services\User\Controllers;
 */
class SetTrafficPatternsV1 extends ServiceAbstract
{
    /**
     * 校验请求参数
     *
     * true = 校验通过 false=校验不通过
     * @return boolean
     */
    public function paramsValidate()
    {
        return $this->_validate($this->params, [
            'userid'  => 'required',
            'patterns'=> 'required|integer|between:1,3',
        ], [
            'userid.required'  => '参数丢失',
            'patterns.required'=> '流量参数丢失',
            'patterns.integer'=> '流量参数错误',
            'patterns.between'=> '流量参数错误'
        ]);
    }

    /**
     * 服务必须实现的方法，因为调用服务会自动调用本方法
     *
     * @return array
     */
    public function run()
    {
        $userModel = UserModel::where('uid', $this->params['userid'])->first();
        $userModel->traffic_patterns = $this->params['patterns'];
        $userModel->save();
        return $this->response();
    }

}