<?php

namespace App\Services\Foundation\Controllers;

use App\Services\ServiceAbstract;

/**
 * 校验验证码
 *
 * 版本号：v1
 *
 * Class CheckVerificationCodeV1
 * @package App\Services\Foundation\Controllers
 */
class CheckVerificationCodeV1 extends ServiceAbstract
{
    /**
     * 校验请求参数
     *
     * true = 校验通过 false=校验不通过
     * @return boolean
     */
    public function paramsValidate()
    {
        return $this->_validate($this->params, [
            'code' => 'required'
        ], [
            'code.required' => '请输入验证码'
        ]);
    }

    /**
     * 服务必须实现的方法，因为调用服务会自动调用本方法
     *
     * @return array
     */
    public function run()
    {
        if ($this->params['code'] != session('milkcaptcha')) {
            $this->error('验证码错误！')->abort();
        }
    }
}
