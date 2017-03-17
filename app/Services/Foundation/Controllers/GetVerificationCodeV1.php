<?php

namespace App\Services\Foundation\Controllers;

use App\Services\ServiceAbstract;
use Gregwar\Captcha\CaptchaBuilder;

/**
 * 获取验证码
 *
 * 版本号：v1
 *
 * Class GetVerificationCodeV1
 * @package App\Services\Foundation\Controllers
 */
class GetVerificationCodeV1 extends ServiceAbstract
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
            'w' => 'required|integer|min:50',
            'h' => 'required|integer|min:20',
        ]);
    }

    /**
     * 服务必须实现的方法，因为调用服务会自动调用本方法
     *
     * @return array
     */
    public function run()
    {
        //生成验证码图片的Builder对象，配置相应属性
        $builder = new CaptchaBuilder;
        //可以设置图片宽高及字体
        $builder->build($width = $this->_params['w'], $height = $this->_params['h'], $font = null);
        //获取验证码的内容
        $phrase = $builder->getPhrase();

        //把内容存入session
        session([
            'milkcaptcha' => $phrase,
        ]);

        //生成图片
        header("Cache-Control: no-cache, must-revalidate");
        header('Content-Type: image/jpeg');
        $builder->output();
    }
}
