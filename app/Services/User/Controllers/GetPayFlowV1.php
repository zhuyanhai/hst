<?php

namespace App\Services\User\Controllers;

use App\Services\ServiceAbstract;
use App\Services\User\Models\AccountLogModel;

/**
 * 获取用户最后一次充值的流量
 *
 * 临时的，1.1.0后续版本要废弃掉
 *
 * 版本号：v1
 *
 * Class CancelFlowLimitV1
 * @package App\Services\User\Controllers;
 */
class GetPayFlowV1 extends ServiceAbstract
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
        $accountLogModel = AccountLogModel::where('uid', $this->_params['userid'])->where('status', 2)->orderBy('id', 'desc')->first();
        if (!$accountLogModel) {
            $this->error('获取失败');
        }

        $price = null;
        $flow  = null;

        if ($accountLogModel->cbid > 0) {
            $cbResult = callService('foundation.GetFlowComboListV1', ['mode' => 'ids', 'ids' => [$accountLogModel->cbid]]);
            if ($cbResult['code'] == 0 && !empty($cbResult['data'])) {
                foreach ($cbResult['data'] as $d) {
                    $price = $d['price'];
                    $flow  = $d['flow'];
                }
            }
        }

        $cnumData = null;
        if ($accountLogModel->cnum > 0) {
            $cnumResult = callService('pay.getCardListV1', ['mode' => 'nums', 'nums' => [$accountLogModel->cnum]]);
            if ($cnumResult['code'] == 0 && !empty($cnumResult['data'])) {
                foreach ($cnumResult['data'] as $d) {
                    $price = $d['price'];
                    $flow  = $d['flow'];
                }
            }
        }


        return $this->response(['flow' => $flow, 'price' => $price]);
    }
}
