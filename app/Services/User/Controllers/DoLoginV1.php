<?php

namespace App\Services\User\Controllers;

use App\Services\ServiceAbstract;
use App\Services\User\Models\UserModel;
use App\Services\User\Helpers\LoginToken;
use Ixudra\Curl\Facades\Curl;

/**
 * 用户登录服务
 *
 * 版本号：v1
 *
 * Class DoLoginV1
 * @package App\Services\User\Controllers;
 */
class DoLoginV1 extends ServiceAbstract
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
            'account'  => 'required',
            'password' => 'required',
        ], [
            'account.required'  => '请输入手机号',
            'password.required' => '请输入密码',
        ]);
    }

    /**
     * 服务必须实现的方法，因为调用服务会自动调用本方法
     *
     * @return array
     */
    public function run()
    {
        $userModel = UserModel::where('phone', $this->params['account'])->first();
        if (!$userModel) {
            $this->error('该帐号不存在');
        }
        if (intval($userModel->frozen) !== 1) {
            if ($userModel->password == md5($this->params['password'])) {

                //VOIP
                //$iModel = new \Common\Model\IcallModel();
                //$iUid = $iModel->phoneGetUid($phone);
                //$udata = $this->user($userModel->uid);
                //$udata['data']['iuid'] = $iUid;

                $result = callService('user.getInfoV1', ['userid' => $userModel->uid]);

                if (intval($result['code']) != 0) {
                    $this->error($result['msg']);
                }

                //临时
                $result['data']['iuid'] = 0;

                //用户登录token
                $result['data']['token'] = LoginToken::build($result['data']['uid'], $result['data']['phone'], $result['data']['password'], $result['data']['createtime']);

                $openVpnServer = config('site.openVpnServer');
                $url = 'http://' . $openVpnServer['ip'] . ':' . $openVpnServer['port'] . '/api/user/syncTrafficPatternsV1';
                $token = LoginToken::build($userModel->uid, $userModel->account, $userModel->password, $userModel->createtime);
                Curl::to($url)
                    ->withHeaders([
                        'HST-BUNDLEID:1212',
                        'HST-SYSTEM:android',
                        'HST-DEVICEMAC:ddddd',
                        'HST-PACKAGE:333',
                        'HST-VERSION:1.0.1',
                        'HST-APPID:hst123456',
                    ])
                    ->withData(array( 'userid' => $userModel->uid, 'token' => $result['data']['token'], 'patterns' => $userModel->traffic_patterns,
                        'allowExternalUpdates' => $userModel->allow_external_updates))
                    ->post();

                return $this->response($result['data'], $result['cookies']);
            }else {
                $this->error('密码错误');
            }
        } else {
            $this->error('该用户已被冻结');
        }
    }
}
