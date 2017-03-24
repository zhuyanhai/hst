<?php

namespace App\Services\Foundation\Controllers;

use App\Services\ServiceAbstract;
use App\Services\Foundation\Models\UserPushXiaomiTokenModel;

/**
 * 设置手机app推送token接口
 *
 * 版本号：v1
 *
 * Class SetMobilePushTokenV1
 * @package App\Services\Foundation\Controllers
 */
class SetMobilePushTokenV1 extends ServiceAbstract
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
            'type' => 'required|string',
            'token'=> 'required',
        ], [
            'type.required'  => '参数type丢失',
            'type.string'  => '参数type丢失',
            'token.required'  => '参数token丢失',
        ]);
    }

    private $_typeMaps = [
        'xiaomi' => '_xiaomi',
    ];

    /**
     * 服务必须实现的方法，因为调用服务会自动调用本方法
     *
     * @return array
     */
    public function run()
    {

        if (!isset($this->_typeMaps[$this->_params['type']])) {
            $this->error('获取失败');
        }

        $method = $this->_typeMaps[$this->_params['type']];
        return $this->$method();
    }

    /**
     * 设置小米推送token
     */
    private function _xiaomi()
    {
        $model = UserPushXiaomiTokenModel::where('device_mac', $this->_params['_apiHeaders']['hst-devicemac'][0])->first();
        if (!$model) {
            $model = new UserPushXiaomiTokenModel();
        }

        $model->device_mac = $this->_params['_apiHeaders']['hst-devicemac'][0];
        $model->device_token = $this->_params['token'];
        $model->device_bundleid = $this->_params['_apiHeaders']['hst-bundleid'][0];
        $model->userid = $this->_params['userid'];
        $model->is_receive = 1;
        $model->system_type = $this->_params['_apiHeaders']['hst-system'][0];
        $model->created_at = time();
        $model->updated_at = time();
        if ($model->save()) {
            return $this->response();
        } else {
            $this->error('设置失败');
        }
    }
}
