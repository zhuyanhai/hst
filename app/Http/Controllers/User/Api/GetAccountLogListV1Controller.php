<?php

namespace App\Http\Controllers\User\Api;

use App\Http\Controllers\ApiController;

/**
 * 获取单个账户充值记录列表
 *
 * 版本号：v1
 *
 * Class GetAccountLogListV1Controller
 * @package App\Http\Controllers\User\Api
 */
class GetAccountLogListV1Controller extends ApiController
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
            'mode'   => 'required|string',
        ], [
            'mode.required'  => '参数mode丢失',
            'mode.string'  => '参数mode丢失',
        ]);
    }

    /**
     * 服务必须实现的方法，因为调用服务会自动调用本方法
     *
     * @return array
     */
    public function run()
    {
        $result = callService('user.getAccountLogListV1', ['userid' => $this->loginUserInfo['uid'], 'mode' => $this->_params['mode']]);

        if ($result['code'] != 0) {
            $this->error($result['msg']);
        }

        return $this->response($result['data']);
    }

}