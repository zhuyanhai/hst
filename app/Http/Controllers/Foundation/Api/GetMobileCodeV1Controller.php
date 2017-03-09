<?php

namespace App\Http\Controllers\Foundation\Api;

use App\Http\Controllers\ApiController;

/**
 * 获取手机验证码接口
 *
 * 版本号：v1
 *
 * Class GetMobileCodeV1Controller
 * @package App\Http\Controllers\Foundation\Api
 */
class GetMobileCodeV1Controller extends ApiController
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
            'phone' => 'required|regex:/^1[34578][0-9]{9}$/',
        ], [
            'phone.required' => '请输入手机号',
            'phone.regex' => '请输入手机号',
        ]);
    }

    /**
     * API 接口对应的执行方法
     *
     * @return \App\Http\Controllers\response
     */
    public function run()
    {
        $result = callService('foundation.getMobileCodeV1', $this->_params);

        if ($result['code'] != 0) {
            $this->error($result['msg']);
        }

        return $this->response();
    }

}
