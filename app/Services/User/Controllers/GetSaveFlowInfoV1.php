<?php

namespace App\Services\User\Controllers;

use App\Services\ServiceAbstract;
use App\Services\User\Models\AccountModel;
use App\Services\User\Models\AccountSaveFlowDayModel;

/**
 * 获取用户节省流量信息
 *
 * 版本号：v1
 *
 * Class GetSaveFlowInfoV1
 * @package App\Services\User\Controllers;
 */
class GetSaveFlowInfoV1 extends ServiceAbstract
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
        ], [
            'userid.required'  => '参数丢失',
        ]);
    }

    /**
     * 服务必须实现的方法，因为调用服务会自动调用本方法
     *
     * @return array
     */
    public function run()
    {
        //1M = 1元

        $model = AccountModel::where('uid', $this->_params['userid'])->first(['tsave']);
        if (!$model) {
            $this->error('帐号不存在');
        }

        //昨日
        $yestoday = date("Ymd",strtotime("-1 day"));
        $saveFlowDayModel = AccountSaveFlowDayModel::where('uid', $this->_params['userid'])->where('created_at', $yestoday)->first(['flow']);
        if ($saveFlowDayModel && $saveFlowDayModel->flow > 0) {
            $yestodaySaveFlow = bcmul($saveFlowDayModel->flow / 1024 / 1024, 0);
        } else {
            $yestodaySaveFlow = '0';
        }

        $tsave = bcmul($model->tsave / 1024, 0);

        //todo 假数据
        return $this->response([
            'yestodaySaveFlow' => $yestodaySaveFlow,
            'totalSaveFlow'    => $tsave,
            'totalSaveMoney'   => $tsave,
        ]);

    }

}