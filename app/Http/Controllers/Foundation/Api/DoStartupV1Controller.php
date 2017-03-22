<?php

namespace App\Http\Controllers\Foundation\Api;

use App\Http\Controllers\ApiController;

/**
 * 手机app启动接口
 *
 * 版本号：v1
 *
 * Class DoStartupV1Controller
 * @package App\Http\Controllers\Foundation\Api
 */
class DoStartupV1Controller extends ApiController
{
    /**
     * 定义接口必须登录才可以被访问
     *
     * @var bool true＝必须登录 false＝可以不登陆就访问
     */
    protected $foreLogin = false;

    /**
     * 校验请求参数
     *
     * true = 校验通过 false=校验不通过
     * @return boolean
     */
    protected function paramsValidate()
    {
        return $this->_validate($this->_params, [
            'patterns' => 'required',
        ], [
            'patterns.required'  => '参数丢失',
        ]);
    }

    /**
     * API 接口对应的执行方法
     *
     * @return \App\Http\Controllers\response
     */
    public function run()
    {
        // start=大启动（进程被杀死后启动） hot=热启动（前后台切换启动）
        if (!in_array($this->_params['patterns'], ['start', 'hot'])) {
            $this->error('参数错误');
        }

        $return = [
            'openVpn' => [
                'certificateUrl' => 'http://hst.bxshare.cn/storage/openvpn_certificate/private.ovpn?v=20170318',
            ],
            'registerProtocol' => 'http://hst.bxshare.cn/storage/register_protocol?v=20170319',
        ];

        //当前写死下载url，后期开发后台配置app更新
        if ($this->_params['patterns'] == 'start') {//大启动
            $result = callService('foundation.checkAppVersionV1', [
                'systemType' => $this->_headers['hst-system'][0],
                'appMark'    => 'haishangtong',
                'packageName'=> $this->_headers['hst-package'][0],
                'version'    => $this->_headers['hst-version'][0],
            ]);

            if ($result['code'] != 0) {
                $this->error($result['msg']);
            }

            $return['versionCheck'] = $result['data'];
        }

        return $this->response($return);

    }

}
