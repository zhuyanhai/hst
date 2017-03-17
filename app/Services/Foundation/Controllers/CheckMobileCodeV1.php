<?php

namespace App\Services\Foundation\Controllers;

use App\Services\ServiceAbstract;
use App\Services\Foundation\Models\CodeModel;

/**
 * 校验手机验证码
 *
 * 版本号：v1
 *
 * Class CheckMobileCodeV1
 * @package App\Services\Foundation\Controllers
 */
class CheckMobileCodeV1 extends ServiceAbstract
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
            'account' => 'required|regex:/^1[34578][0-9]{9}$/',
            'code' => 'required',
        ], [
            'code.required' => '请输入验证码',
            'account.required' => '请输入手机号',
            'account.regex' => '请输入手机号',
        ]);
    }

    /**
     * 服务必须实现的方法，因为调用服务会自动调用本方法
     *
     * @return array
     */
    public function run()
    {
        $phone = $this->_params['account'];
        $code  = $this->_params['code'];


        $codeModel = CodeModel::where('phone', $phone)->where('code', $code)->first();
        if ($codeModel) {
            return $this->response();
        }

        $this->error('验证码错误！');
    }
}
