<?php

namespace App\Services\User\Controllers;

use App\Services\ServiceAbstract;
use App\Services\User\Models\AccountModel;
use App\Services\User\Models\FlowCostLogModel;

/**
 * 获取用户流量信息
 *
 * 剩余总流量
 * 今日已用流量
 * 今日已省流量
 *
 * 版本号：v1
 *
 * Class GetFlowInfoV1
 * @package App\Services\User\Controllers;
 */
class GetFlowInfoV1 extends ServiceAbstract
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
            'userid' => 'required',
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
        $accountModel = AccountModel::where('uid', $this->_params['userid'])->first();
        if (!$accountModel) {
            $this->error('账户不存在');
        }

        return $this->response([
            //用户当前剩余总流量 KB
            'flowLeft'    => (float)(($accountModel->flowleft < 0)?0:$accountModel->flowleft),
            //用户当天使用流量 KB
            'dayCostFlow' => $accountModel->dcost,
            //用户当天省流量 KB
            'daySaveFlow' => $accountModel->dsave,
        ]);
    }
}
