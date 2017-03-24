<?php

namespace App\Http\Controllers\Foundation\Api;

use App\Http\Controllers\ApiController;

/**
 * 设置手机app推送token接口
 *
 * 版本号：v1
 *
 * Class SetMobilePushTokenV1Controller
 * @package App\Http\Controllers\Foundation\Api
 */
class SetMobilePushTokenV1Controller extends ApiController
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
            'type' => 'required|string',
            'token'=> 'required',
        ], [
            'type.required'  => '参数type丢失',
            'type.string'  => '参数type丢失',
            'token.required'  => '参数token丢失',
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
        if (is_null($this->loginUserInfo)) {
            $this->_params['userid'] = 0;
        } else {
            $this->_params['userid'] = $this->loginUserInfo['uid'];
        }
        $result = callService('foundations.setMobilePushTokenV1', $serviceParams);

        if ($result['code'] != 0) {
            $this->error($result['msg']);
        }

        return $this->response();

    }

}
