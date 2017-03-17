<?php

namespace App\Http\Controllers\User\Api;

use App\Http\Controllers\ApiController;

/**
 * 用户登录接口
 *
 * 版本号：v1
 *
 * Class SetIdCardPicV1Controller
 * @package App\Http\Controllers\User\Api
 */
class SetIdCardPicV1Controller extends ApiController
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
            'personFrontPic' => 'required',//身份证正面图片
            'personBackPic' => 'required',//身份证背面图片
        ], [
            'userid.required'  => '参数丢失',
            'personFrontPic.required' => '请上传身份证正面照片',
            'personBackPic.required' => '请上传身份证背面照片',
        ]);
    }

    /**
     * API 接口对应的执行方法
     *
     * @return \App\Http\Controllers\response
     */
    public function run()
    {
        $result = callService('user.setIdCardPicV1', $this->_params);

        if ($result['code'] != 0) {
            $this->error($result['msg']);
        }

        return $this->response();
    }

}