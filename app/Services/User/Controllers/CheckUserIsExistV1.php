<?php

namespace App\Services\User\Controllers;

use App\Services\ServiceAbstract;
use App\Services\User\Models\UserModel;

/**
 * 根据各种条件检测用户是否存在
 *
 * 版本号：v1
 *
 * Class CheckUserIsExistV1
 * @package App\Services\User\Controllers;
 */
class CheckUserIsExistV1 extends ServiceAbstract
{
    /**
     * 校验请求参数
     *
     * true = 校验通过 false=校验不通过
     * @return boolean
     */
    public function paramsValidate()
    {
        return true;
    }

    /**
     * 服务必须实现的方法，因为调用服务会自动调用本方法
     *
     * @return array
     */
    public function run()
    {
        $selector = null;

        //根据手机号检测
        if (isset($this->_params['phone']) && !empty($this->_params['phone'])) {
            $selector = UserModel::where('phone', $this->_params['phone']);
        }

        //根据用户id检测
        if (isset($this->_params['userid']) && !empty($this->_params['userid'])) {
            $selector = UserModel::where('uid', $this->_params['userid']);
        }

        if (is_null($selector)) {
            $this->error('该帐号不存在');
        }

        $userModel = $selector->first();

        if (!$userModel) {
            $this->error('该帐号不存在');
        }

        return $this->response($userModel->toArray());
    }
}
