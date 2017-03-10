<?php

namespace App\Services\User\Controllers;

use App\Services\ServiceAbstract;
use Ixudra\Curl\Facades\Curl;
use App\Services\User\Helpers\LoginToken;
use App\Services\User\Helpers\User;
use Illuminate\Support\Facades\Redis;

/**
 * 设置用户省流量模式
 *
 * 版本号：v1
 *
 * Class SetTrafficPatternsV1
 * @package App\Services\User\Controllers;
 */
class SetTrafficPatternsV1 extends ServiceAbstract
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
            'userid'  => 'required',
            'patterns'=> 'required|integer|between:1,3',
        ], [
            'userid.required'  => '参数丢失',
            'patterns.required'=> '流量参数丢失',
            'patterns.integer'=> '流量参数错误',
            'patterns.between'=> '流量参数错误'
        ]);
    }

    /**
     * 服务必须实现的方法，因为调用服务会自动调用本方法
     *
     * @return array
     */
    public function run()
    {
        $userModel = User::getBaseInfoByUserid($this->params['userid']);
        if (!$userModel) {
            return $this->error('用户不存在');
        }
        $userModel->traffic_patterns = $this->params['patterns'];
        if ($userModel->save()) {
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
                ->withData(array( 'userid' => $this->params['userid'], 'token' => $token, 'patterns' => $this->params['patterns']))
                ->post();


//            Redis::hmset('b', ['name'=> 'dd', 'age'=> 123]);
//            $d = Redis::hget('b', 'name');
//            print_r($d);exit;


            //todo log 系统
        }
        return $this->response();
    }

}