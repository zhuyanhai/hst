<?php

namespace App\Services\User\Controllers;

use App\Services\ServiceAbstract;
use App\Services\User\Helpers\LoginToken;
use App\Services\User\Helpers\User;

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
        return $this->_validate($this->_params, [
            'userid'  => 'required',
            'patterns'=> 'required|integer|between:1,3',
            'allowExternalUpdates' => 'required|integer|between:0,1',
        ], [
            'userid.required'  => '参数丢失',
            'patterns.required'=> '流量参数丢失',
            'patterns.integer'=> '流量参数错误',
            'patterns.between'=> '流量参数错误',
            'allowExternalUpdates.required'=> '更新状态参数丢失',
            'allowExternalUpdates.integer'=> '更新状态参数错误',
            'allowExternalUpdates.between'=> '更新状态参数错误'
        ]);
    }

    /**
     * 服务必须实现的方法，因为调用服务会自动调用本方法
     *
     * @return array
     */
    public function run()
    {
        $userModel = User::getBaseInfoByUserid($this->_params['userid']);
        if (!$userModel) {
            return $this->error('用户不存在');
        }
        $userModel->traffic_patterns = $this->_params['patterns'];
        if (intval($this->_params['patterns']) === 1) {//正常模式，必须允许
            $this->_params['allowExternalUpdates'] = 1;
        }
        $userModel->allow_external_updates = $this->_params['allowExternalUpdates'];
        if ($userModel->save()) {

            //设置用户信息到openVpn
            $token = LoginToken::build($userModel->uid, $userModel->phone, $userModel->password, $userModel->createtime);
            User::setInfoToOpenVpn($this->_params['userid'], $token, $this->_params['patterns'], $this->_params['allowExternalUpdates'],
                $this->_params['_apiHeaders']);

            //todo log 系统
        }
        return $this->response();
    }

}
