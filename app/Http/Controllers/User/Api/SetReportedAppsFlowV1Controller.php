<?php

namespace App\Http\Controllers\User\Api;

use App\Http\Controllers\ApiController;

/**
 * 上报用户手机上各app消耗流量的日志接口
 *
 * 版本号：v1
 *
 * Class SetReportedAppsFlowV1Controller
 * @package App\Http\Controllers\User\Api
 */
class SetReportedAppsFlowV1Controller extends ApiController
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
            'contents' => 'required',
        ], [
            'contents.required'  => '参数丢失',
        ]);
    }

    /**
     * API 接口对应的执行方法
     *
     * @return \App\Http\Controllers\response
     */
    public function run()
    {
        $result = callService('user.setReportedAppsFlowV1', ['userid' => $this->loginUserInfo['uid'], 'contents' => $this->_params['contents']]);

        if ($result['code'] != 0) {
            $this->error($result['msg']);
        }

        return $this->response();
    }

}