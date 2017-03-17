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

        //当前写死下载url，后期开发后台配置app更新

        $return = [
            'versionCheck' => [
                'version'     => '1.1.0',
                'title'       => '有新版本',
                'content'     => '有新版本',
                'downloadUrl' => 'http://hst.bxshare.cn/',
            ],
            'openVpn' => [
                'certificateUrl' => 'http://hst.bxshare.cn/',
            ],
        ];

        return $this->response($return);

    }

}
