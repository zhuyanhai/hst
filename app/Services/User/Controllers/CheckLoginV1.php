<?php

namespace App\Services\User\Controllers;

use App\Services\ServiceAbstract;
use App\Services\User\Helpers\LoginToken;
use App\Services\User\Helpers\User;

/**
 * 通过token检测用户是否登录服务
 *
 * 版本号：v1
 *
 * Class CheckLoginV1
 * @package App\Services\User\Controllers;
 */
class CheckLoginV1 extends ServiceAbstract
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
            'token' => 'required|string',
        ], [
            'token.required'  => '参数错误',
            'token.string' => '参数错误',
        ]);
    }

    /**
     * 服务必须实现的方法，因为调用服务会自动调用本方法
     *
     * @return array
     */
    public function run()
    {
        $userid = LoginToken::stripUserid($this->_params['token']);
        file_put_contents('/tmp/iio',$userid.PHP_EOL, 8);

        $userModel = User::getBaseInfoByUserid($userid);

        $checkResult = LoginToken::check($this->_params['token'], $userModel->uid, $userModel->phone, $userModel->password, $userModel->createtime);

        if (!$checkResult) {
            $this->error('该帐号不存在');
        }


        if (intval($userModel->frozen) !== 1) {
            $this->response();
        } else {
            $this->error('该用户已被冻结');
        }
    }
}
