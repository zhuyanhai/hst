<?php

namespace App\Http\Controllers\Pay\Api;

use App\Http\Controllers\ApiController;

/**
 * 用户登录接口
 *
 * 版本号：v1
 *
 * Class CardRechargeV1Controller
 * @package App\Http\Controllers\Pay\Api
 */
class CardRechargeV1Controller extends ApiController
{
    /**
     * 校验请求参数
     *
     * true = 校验通过 false=校验不通过
     * @return boolean
     */
    protected function paramsValidate()
    {
        return $this->_validate($this->_params, [
            'phone'   => 'required',
            'cardNum' => 'required|max:11',
            'cardPwd' => 'required|max:6',
            'secret'  => 'required',
        ], [
            'phone.required'  => '请输入手机号',
            'cardNum' => '卡号错误',
            'cardPwd' => '卡密码错误',
            'secret.required' => '参数错误',
        ]);
    }

    /**
     * API 接口对应的执行方法
     *
     * @return \App\Http\Controllers\response
     */
    public function run()
    {
        $result = callService('pay.cardRechargeV1', $this->_params);

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
