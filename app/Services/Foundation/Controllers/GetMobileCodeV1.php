<?php

namespace App\Services\Foundation\Controllers;

use App\Services\ServiceAbstract;
use App\Services\Foundation\Models\CodeModel;
use App\Services\Foundation\Helpers\Sms;

/**
 * 获取手机验证码
 *
 * 版本号：v1
 *
 * Class GetMobileCodeV1
 * @package App\Services\Foundation\Controllers
 */
class GetMobileCodeV1 extends ServiceAbstract
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
            'phone' => 'required|regex:/^1[34578][0-9]{9}$/',
        ], [
            'phone.required' => '请输入手机号',
            'phone.regex' => '请输入手机号',
        ]);
    }

    /**
     * 服务必须实现的方法，因为调用服务会自动调用本方法
     *
     * @return array
     */
    public function run()
    {
        $phone = $this->params['phone'];
        $code  = rand(100000, 999999);

        CodeModel::where('phone', $phone)->delete();

        $codeModel = new CodeModel();
        $codeModel->phone = $phone;
        $codeModel->code = $code;

        if ($codeModel->save()) {
            //发送短信
            $return = Sms::send($phone, Sms::TPL_1, '#code#='.$code);
            if (intval($return['status']) === 0) {
                return $this->response();
            }
            $this->error($return['message']);
        } else {
            $this->error('获取失败，请稍侯再试！');
        }
    }
}
