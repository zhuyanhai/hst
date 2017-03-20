<?php

namespace App\Http\Controllers\User\Api;

use App\Http\Controllers\ApiController;

/**
 * 用户登录接口
 *
 * 版本号：v1
 *
 * Class DoLoginV1Controller
 * @package App\Http\Controllers\User\Api
 */
class DoLoginV1Controller extends ApiController
{
    /**
     * 定义接口必须登录才可以被访问
     *
     * @var bool true＝必须登录 false＝可以不登陆就访问
     */
    protected $foreLogin = false;

    /**
     * 校验请求参数
     *
     * true = 校验通过 false=校验不通过
     * @return boolean
     */
    protected function paramsValidate()
    {
        return $this->_validate($this->_params, [
            'account'  => 'required',
            'password' => 'required',
        ], [
            'account.required'  => '请输入手机号',
            'password.required' => '请输入密码',
        ]);
    }

    /**
     * API 接口对应的执行方法
     *
     * @return \App\Http\Controllers\response
     */
    public function run()
    {
        $serviceParams = $this->_params;
        $serviceParams['_apiHeaders'] = $this->_headers;
        $result = callService('user.doLoginV1', $serviceParams);

        if ($result['code'] != 0) {
            $this->error($result['msg']);
        }

        $cookies = [
            'hst_token' => $result['data']['token'],
        ];

        return $this->response($result['data'], $cookies);

//        session([
//            'userid' => $accountModel->userid,
//            'roleid' => $accountModel->roleid,
//            'lock_screen' => 0,
//        ]);
//
//        $this->setCookie('admin_username', $this->_args['username'])
//            ->setCookie('userid', $accountModel->userid);
    }

}
