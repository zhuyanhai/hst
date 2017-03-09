<?php

namespace App\Http\Controllers\User\Api;

use App\Http\Controllers\ApiController;

/**
 * 用户登录接口
 *
 * 版本号：v1
 *
 * Class SetTrafficPatternsV1Controller
 * @package App\Http\Controllers\User\Api
 */
class SetTrafficPatternsV1Controller extends ApiController
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
        ], [
            'userid.required'  => '参数丢失',
            'patterns.required'=> '流量参数丢失',
            'patterns.integer'=> '流量参数错误',
            'patterns.between'=> '流量参数错误'
        ]);
    }

    /**
     * API 接口对应的执行方法
     *
     * @return \App\Http\Controllers\response
     */
    public function run()
    {
        $result = callService('user.SetTrafficPatternsV1', $this->_params);

        if ($result['code'] != 0) {
            $this->error($result['msg']);
        }

        return $this->response();
    }

}