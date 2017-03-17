<?php

namespace App\Http\Controllers\User\Api;

use App\Http\Controllers\ApiController;

/**
 * 用户注册接口
 *
 * 版本号：v1
 *
 * Class DoRegisterV1Controller
 * @package App\Http\Controllers\User\Api
 */
class DoRegisterV1Controller extends ApiController
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
            'account' => 'required',//登录帐号
            'password' => 'required',//登录密码
//          'vcode' => 'required',//校验码
//          'code' => 'required',//手机验证码
//          'pv' => 'required',//系统类型 android
//          'personid' => 'required',//身份证号
//          'personFrontPic' => 'required',//身份证正面图片
//          'personBackPic' => 'required',//身份证背面图片
        ], [
            'account.required' => '请输入手机号',
            'password.required' => '请输入密码',
//          'vcode.required' => '参数错误',
//          'code.required' => '请输入验证码',
//          'personid.required' => '请输入身份证号',
//          'pv.required' => '参数错误',
//          'personFrontPic.required' => '请上传身份证正面照片',
//          'personBackPic.required' => '请上传身份证背面照片',
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
        $result = callService('user.doRegisterV1', $serviceParams);

        if ($result['code'] != 0) {
            $this->error($result['msg']);
        }

        $result['cookies'] = [
            ['hst_token' => $result['data']['token']],
        ];

        return $this->response($result['data'], $result['cookies']);
    }

}