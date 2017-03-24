<?php

namespace App\Http\Controllers\User\Api;

use App\Http\Controllers\ApiController;

/**
 * 获取用户最后一次充值的流量
 *
 * 版本号：v1
 *
 * Class GetPayFlowV1Controller
 * @package App\Http\Controllers\User\Api
 */
class GetPayFlowV1Controller extends ApiController
{
    /**
     * 校验请求参数
     *
     * true = 校验通过 false=校验不通过
     * @return boolean
     */
    protected function paramsValidate()
    {
        return true;
    }

    /**
     * API 接口对应的执行方法
     *
     * @return \App\Http\Controllers\response
     */
    public function run()
    {
        $result = callService('user.getPayFlowV1', ['userid' => $this->loginUserInfo['uid']]);

        if ($result['code'] != 0) {
            $this->error($result['msg']);
        }

        return $this->response($result['data']);

    }

}
