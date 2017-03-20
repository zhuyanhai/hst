<?php

namespace App\Services\User\Controllers;

use App\Services\ServiceAbstract;
use App\Services\User\Models\UserAppsFlowConsumeModel;

/**
 * 上报用户手机上各app消耗流量的日志接口
 *
 * 版本号：v1
 *
 * Class SetReportedAppsFlowV1
 * @package App\Services\User\Controllers;
 */
class SetReportedAppsFlowV1 extends ServiceAbstract
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
            'contents'=> 'required',
        ], [
            'userid.required'  => '参数丢失',
            'contents.required'=> '参数丢失',
        ]);
    }

    /**
     * 服务必须实现的方法，因为调用服务会自动调用本方法
     *
     * @return array
     */
    public function run()
    {
        $contents = json_decode($this->_params['contents'], true);
        foreach ($contents as $content) {
            try {//因为userid+dates是唯一键，所以忽略异常，后续完善日志系统
                UserAppsFlowConsumeModel::create([
                    'userid'   => $this->_params['userid'],
                    'dates'    => preg_replace('%-%i', '', $content['date']),
                    'contents' => json_encode($content['data'], JSON_UNESCAPED_UNICODE),
                ]);
            } catch (\Exception $e) {
                //todo log
            }

        }
        return $this->response();
    }

}
