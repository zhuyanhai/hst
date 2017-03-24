<?php

namespace App\Services\User\Controllers;

use App\Services\ServiceAbstract;
use App\Services\User\Models\AccountLogModel;
use App\Services\User\Helpers\User;
use App\Libraries\Utils;

/**
 * 获取单个账户充值记录列表
 *
 * 版本号：v1
 *
 * Class GetAccountLogListV1
 * @package App\Services\User\Controllers;
 */
class GetAccountLogListV1 extends ServiceAbstract
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
            'userid' => 'required|integer',
            'mode'   => 'required|string',
        ], [
            'userid.required'  => '参数userid丢失',
            'userid.integer'  => '参数userid丢失',
            'mode.required'  => '参数mode丢失',
            'mode.string'  => '参数mode丢失',
        ]);
    }

    private $_modeMaps = [
        'user_pay_success' => '_getUserPaySuccess',
    ];

    /**
     * 服务必须实现的方法，因为调用服务会自动调用本方法
     *
     * @return array
     */
    public function run()
    {
        $userModel = User::getBaseInfoByUserid($this->_params['userid']);
        if (!$userModel) {
            $this->error('获取失败');
        }
        if (!isset($this->_modeMaps[$this->_params['mode']])) {
            $this->error('获取失败');
        }

        $method = $this->_modeMaps[$this->_params['mode']];
        return $this->$method($userModel);

    }

    /**
     * 获取用户支付成功的充值日志分页列表
     *
     * @param UserModel $userModel
     * @return array
     */
    private function _getUserPaySuccess($userModel)
    {
        $id = 0;
        if (isset($this->_params['lastId'])) {
            $id = intval($this->_params['lastId']);
        }
        //每页需要数量
        $pageCount = 10;
        //实际获取数量，为判断是否到达末页，所以比$pageCount大1
        $count = 11;

        $selector = AccountLogModel::where('uid', $userModel->uid);
        if ($id > 0) {
            $selector->where('id', '<', $id);
        }
        $accountLogModel = $selector->where('status', 2)->limit($count)->orderBy('id', 'desc')->get([
            'id', 'payment', 'recharge_time', 'cbid', 'jine', 'cnum'
        ]);

        $return = [
            'lastId' => 0,
            'isEnd'  => 0,
            'list'   => [],
        ];
        if ($accountLogModel->count() > 0) {

            $cbidArray = [];
            $cnumArray = [];
            foreach ($accountLogModel as $v) {
                if ($v->cbid > 0) {
                    array_push($cbidArray, $v->cbid);
                }
                if ($v->cnum > 0) {
                    array_push($cnumArray, $v->cnum);
                }
            }

            $cbidData = [];
            if (count($cbidArray) > 0) {
                $cbResult = callService('foundation.GetFlowComboListV1', ['mode' => 'ids', 'ids' => $cbidArray]);
                if ($cbResult['code'] == 0 && !empty($cbResult['data'])) {
                    foreach ($cbResult['data'] as $d) {
                        $cbidData[$d['id']] = $d['price'];
                    }
                }
            }

            $cnumData = [];
            if (count($cnumArray) > 0) {
                $cnumResult = callService('pay.getCardListV1', ['mode' => 'nums', 'nums' => $cnumArray]);
                if ($cnumResult['code'] == 0 && !empty($cnumResult['data'])) {
                    foreach ($cnumResult['data'] as $d) {
                        $cnumData[$d['num']] = $d['price'];
                    }
                }
            }

            if ($accountLogModel->count() <= $pageCount) {
                $return['isEnd'] = 1;
            }

            $i = 0;
            foreach ($accountLogModel as $v) {

                if ($i >= $pageCount) {
                    break;
                }

                $payment = ($v->payment == 1)?'支付宝':(($v->payment == 2)?'微信':'充值卡');

                $jine = (float)0;
                if ($v->cbid > 0) {
                    if (!empty($cbidData) && isset($cbidData[$v->cbid])) {
                        $jine = (float)$cbidData[$v->cbid];
                    } else {
                        $jine = (float)0;
                    }
                } elseif($v->cnum > 0) {
                    if (!empty($cnumData) && isset($cnumData[$v->cnum])) {
                        $jine = (float)$cnumData[$v->cnum];
                    } else {
                        $jine = (float)0;
                    }
                } else {
                    $jine = (float)$v->jine;
                }
                array_push($return['list'], [
                    'id'      => $v->id,
                    'payment' => $payment,
                    'jine'    => $jine,
                    'phone'   => $userModel->phone,
                    'time'    => (empty($v->recharge_time))?0:date('Y/m/d', $v->recharge_time),
                ]);
                $return['lastId'] = $v->id;
                $i++;
            }
        } else {
            $return['isEnd'] = 1;
        }

        return $this->response($return);
    }

}