<?php

namespace App\Http\Controllers\Foundation\Api;

use App\Http\Controllers\ApiController;

/**
 * 获取流量包套餐列表
 *
 * 版本号：v1
 *
 * Class getFlowComboListV1Controller
 * @package App\Http\Controllers\Foundation\Api
 */
class getFlowComboListV1Controller extends ApiController
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
        $result = callService('foundation.getFlowComboListV1', $this->_params);

        if ($result['code'] != 0) {
            $this->error($result['msg']);
        }

        return $this->response($result['data']);
    }

}
