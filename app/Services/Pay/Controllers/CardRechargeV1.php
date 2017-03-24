<?php

namespace App\Services\Foundation\Controllers;

use App\Services\ServiceAbstract;
use App\Services\User\Models\CardModel;

/**
 * 校验app是否需要升级
 *
 * 版本号：v1
 *
 * Class CardRechargeV1
 * @package App\Services\Foundation\Controllers
 */
class CardRechargeV1 extends ServiceAbstract
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
            'phone'   => 'required',
            'cardNum' => 'required|max:11',
            'cardPwd' => 'required|max:6',
            'secret'  => 'required',
        ], [
            'phone.required'  => '请输入手机号',
            'cardNum' => '卡号错误',
            'cardPwd' => '卡密码错误',
            'secret.required' => '参数错误',
        ]);
    }

    /**
     * 服务必须实现的方法，因为调用服务会自动调用本方法
     *
     * @return array
     */
    public function run()
    {
        $cardNum = $this->_params['cardNum'];
        $cardPwd = $this->_params['cardPwd'];
        $phone   = $this->_params['phone'];

        //原因 - 后台充值使用
        $yuanyin = isset($this->_params['yuanyin'])?$this->_params['yuanyin']:'';
        //分类 - 后台充值使用
        $fenlei = isset($this->_params['fenlei'.$yuanyin])?$this->_params['fenlei'.$yuanyin]:'';
        //说明 - 后台充值使用
        $shuoming = isset($this->_params['shuoming'])?htmlspecialchars(trim($this->_params['shuoming'])):'';
        //金额 - 后台充值使用
        $jine = isset($this->_params['jine'])?round($this->_params['jine'], 2):0;

        //检测用户是否存在
        $checkResult = callService('user.CheckUserIsExistV1', ['phone' => $phone]);
        if ($checkResult['code'] != 0) {
            $this->error('此账户不存在');
        } else {
            $userInfo = $checkResult['data'];
        }

        //检测卡号＋密码
        $cardModel = CardModel::where('num', $cardNum)->where('pwd', $cardPwd)->first();
        if (!$cardModel) {
             $this->error('卡号或密码错误');
        }

        if ($cardModel->is_recharge != 0) {
            $this->error('该卡已被充值');
        }

        if ($cardModel->is_frozen != 0) {
            $this->error('该卡已被冻结');
        }

        //卡对应的流量
        $flowKB = $cardModel->flow * 1024;
        $flowMB = $cardModel->flow;

        //获取用户账号是否存在
        $accountResult = callService('user.getAccountV1', ['userid' => $userInfo['userid']]);
        if ($accountResult['code'] != 0) {
            $this->error('此账户不存在');
        } else {
            $accountInfo = $accountResult['data'];
        }

        //1、普通流量未用完，不能充值包月卡
        if(!$accountInfo['isby'] && $accountInfo['flowleft'] > 0 && $cardModel->type == 2) {
            $this->error('请用完普通流量再充值包月套餐');
        }

        //2、包月期内，不能充值普通套餐
        if($accountInfo['isby'] && $cardModel->type == 1) {
            $this->error('包月期内不能充值普通流量套餐');
        }

        //操作员 下个版本加上 todo
        $opaid = 0;//操作员 $_SESSION['userid']

        //从x日起，到下个月x-1日需要多少天
        $rangeDay = (int)((strtotime(date('Ymd').'000000 + 1 month') - strtotime(date('Ymd').'000000'))/60/60/24) - 1;

        $accountSave = [
            'cbname' => $cardModel->name,
            'cbid'   => $cardModel->cbid,
            'cbflow' => $flowKB,//充值卡对应的流量 kb
            'warn80' => 0,
            'warn100'=> 0,
            'rccount'=> array('exp','rccount+1'),
            'islimit'=> 0,
            'flowday'=> 0
        ];


        if ($cardModel->type === 2) {//若本次充值是包月卡

            //若用户上次冲的也是包月卡，且套餐与本次充值一样，则流量叠
            $flowLeft = $flowKB;
            if ($accountInfo['isby'] && $accountInfo['cbid'] == $cardModel->cbid && $accountInfo['flowleft'] > 0) {
               $flowLeft += $accountInfo['flowleft'];
            }

            $bystime = strtotime(date('Ymd').'000000');
            $byetime = strtotime(date('Ymd').'235959 + '.$rangeDay.' day');

            //日均限额
            $bydaylimit = $flowKB / $rangeDay;
            $accountSave = array_merge($accountSave, [
                'flowleft'=>$flowLeft, //流量
                'isby'=>1, //包月
                'bystime'=>$bystime, //开始时间
                'byetime'=>$byetime, //结束时间
                'bydaylimit'=>$bydaylimit, //每日使用流量的限额 kb
            ]);
        } else {//普通充值卡

            $flowLeft = $accountInfo['flowleft'] + $flowKB;
            $accountSave = array_merge($accountSave, [
                'flowleft' => $flowLeft,
                'isby' => 0, // 非包月
                'bystime' => '',
                'byetime' => '',
                'bydaylimit'=>0, //非包月，不做每日限制
            ]);
        }

        $cardModel->is_recharge = 1;
        if ($cardModel->save()) {
            //更新用户的账户信息
            $accountSaveResult = callService('user.setAccountV1', ['userid' => $userInfo['uid'], 'setInfo' => $accountSave, 'setLogInfo' => [
                'opaid' => $opaid,//操作员id
                'uid'   => $userInfo['uid'],//用户id
                'cbname'=> $cardModel->name,//卡名称
                'cnum'  => $cardModel->num,//卡号
                'status'=> 2,//已支付
                'recharge_time' => time(),//充值时间
                'yuanyin' => $yuanyin,//充值原因 - 后台使用
                'fenlei'  => $fenlei,//分类原因 - 后台使用
                'shuoming'=> $shuoming,//充值说明 - 后台使用
                'jine' => $jine//充值金额 - 后台使用
            ]]);
            if ($accountSaveResult['code'] != 0) {
                $cardModel->is_recharge = 0;
                $cardModel->save();
                $this->error($accountSaveResult['msg']);
            }

            //取消限速
            $cancelResult = callService('user.cancelFlowLimitV1', ['userid' => $userInfo['uid']]);
            if ($cancelResult['code'] != 0) {

            }

//            //如果用户在船上，则推送给船上路由器
//            $apiCtrl = new \Api\Controller\IndexController();
//            $apiCtrl->pushRechargeInfo($userD['phone'], $flowK);
//            $apiCtrl->pushRechargeMsgToUser($userD['phone'],$flowM.'M');

            return $this->response();
        } else {
            $this->error('充值失败');
        }

    }
}
