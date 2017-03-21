<?php

namespace App\Http\Controllers\User\Api;

use App\Http\Controllers\ApiController;

/**
 * 接收用户反馈的接口
 *
 * 版本号：v1
 *
 * Class SetFeedbackV1Controller
 * @package App\Http\Controllers\User\Api
 */
class SetFeedbackV1Controller extends ApiController
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
            'contentType' => 'required|integer',
            'contents'    => 'required|string|max:140',

        ], [
            'contentType.required'  => '参数contentType丢失',
            'contentType.integer'  => '参数contentType丢失',
            'contents.required'  => '参数contents丢失',
            'contents.string'  => '参数contents丢失',
            'contents.max'  => '内容必须小于140字',
        ]);
    }

    /**
     * API 接口对应的执行方法
     *
     * @return \App\Http\Controllers\response
     */
    public function run()
    {
        $result = callService('user.setFeedbackV1', [
            'userid'      => $this->loginUserInfo['uid'],
            'contentType' => $this->_params['contentType'],
            'contents'    => $this->_params['contents'],
        ]);

        if ($result['code'] != 0) {
            $this->error($result['msg']);
        }


        return $this->response();
    }

}
