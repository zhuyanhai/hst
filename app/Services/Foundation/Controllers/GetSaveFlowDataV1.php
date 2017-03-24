<?php

namespace App\Services\Foundation\Controllers;

use App\Services\ServiceAbstract;
use App\Services\User\Models\AccountModel;
use App\Services\User\Models\AccountSaveFlowDayModel;
use App\Services\User\Models\AccountSaveFlowMonthModel;
use App\Services\User\Models\AccountSaveFlowYearModel;

/**
 * 获取 省流量的数据 | redis
 *
 * 版本号：v1
 *
 * Class GetSaveFlowDataV1
 * @package App\Services\Foundation\Controllers
 */
class GetSaveFlowDataV1 extends ServiceAbstract
{
    /**
     * 校验请求参数
     *
     * true = 校验通过 false=校验不通过
     * @return boolean
     */
    public function paramsValidate()
    {
        return true;
    }

    /**
     * 服务必须实现的方法，因为调用服务会自动调用本方法
     *
     * @return array
     */
    public function run()
    {
        $data = json_decode($this->_params['info']);
        foreach ($data as $key => $val) {
            if (is_object($val)) {
                $info = (array)$val;
                //判断年月日
                $type = substr_count($info['date'], '-');
                switch ($type) {
                    case 2://日
                        //判断是否等于当日 | 等于覆盖 | 不等于跳过
                        if (date('Y-m-d', time()) == $info['date']) {
                            $this->startData('day', $info['uid'], $info['flow'], $info['date']);
                        }
                        break;
                    case 1://月
                        //判断是否等于当月 | 等于覆盖 | 不等于跳过
                        if (date('Y-m', time()) == $info['date']) {
                            $this->startData('month', $info['uid'], $info['flow'], $info['date']);
                        }
                        break;
                    case 0://年
                        //判断是否等于当年 | 等于覆盖 | 不等于跳过
                        if (date('Y', time()) == $info['date']) {
                            $this->startData('year', $info['uid'], $info['flow'], $info['date']);
                        }
                        break;
                    default;
                }
            }
        }
        return $this->response();
    }


    /**
     * 处理逻辑
     *
     * @param $type
     * @param $uid
     * @param $flow
     * @param $date
     */
    public function startData($type, $uid, $flow, $date)
    {

        $model = null;

        if ($type == 'day') {
            $model = AccountSaveFlowDayModel::where('uid', $uid)->first();
            if (!$model) {
                $model = new AccountSaveFlowDayModel();
                $model->uid = $uid;
                $model->flow = $flow;
                $model->created_at = str_replace("-", "", $date);
                $model->save();
            } else {
                $model->flow = $flow;
                $model->save();
            }
            $user = AccountModel::where('uid', $uid)->first();
            if ($user) {
                $user->dsave = bcdiv($flow, 1024, 0);
                $user->save();
            }
        }

        if ($type == 'month') {
            $model = AccountSaveFlowMonthModel::where('uid', $uid)->first();
            if (!$model) {
                $model = new AccountSaveFlowMonthModel();
                $model->uid = $uid;
                $model->flow = $flow;
                $model->created_at = str_replace("-", "", $date);
                $model->save();
            } else {
                $model->flow = $flow;
                $model->save();
            }
            $user = AccountModel::where('uid', $uid)->first();
            if ($user) {
                $user->msave = bcdiv($flow, 1024, 0);
                $user->save();
            }
        }

        if ($type == 'year') {
            $model = AccountSaveFlowYearModel::where('uid', $uid)->first();
            if (!$model) {
                $model = new AccountSaveFlowYearModel();
                $model->uid = $uid;
                $model->flow = $flow;
                $model->created_at = str_replace("-", "", $date);
                $model->save();
            } else {
                $model->flow = $flow;
                $model->save();
            }
            $user = AccountModel::where('uid', $uid)->first();
            if ($user) {
                $user->ysave = bcdiv($flow, 1024, 0);
                $user->save();
            }
        }

    }


}
