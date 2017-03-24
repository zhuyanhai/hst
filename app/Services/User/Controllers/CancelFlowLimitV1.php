<?php

namespace App\Services\User\Controllers;

use App\Services\ServiceAbstract;
use App\Services\User\Models\UserModel;
use App\Services\User\Helpers\User;

/**
 * 取消用户的限速
 *
 * 版本号：v1
 *
 * Class CancelFlowLimitV1
 * @package App\Services\User\Controllers;
 */
class CancelFlowLimitV1 extends ServiceAbstract
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
        $userModel = User::getBaseInfoByUserid($this->_params['userid']);
        if (!$userModel) {
            $this->error('用户不存在');
        }

        $result = callService('ship.getOnlineUserSidV1', $this->_params);
        if ($result['code'] != 0) {
            $this->error($result['msg']);
        }

        if (!empty($result['data'])) {
            foreach ($result['data'] as $relation) {
                //todo 等到融云对接上
                //$apiCtrl->systemToUser(['usrname' => [$userModel->phone]], 27, 's'.$relation['sid']);

                $toId = $to ? $to : $this->routerPushId;
                $content = array(
                    'srcMsgQid' =>  $this->srcMsgQid ? $this->srcMsgQid : '',
                    'user'      =>  array('uid'=>-1),
                    'type'      =>  $type,
                    'content'   =>  $content,
                    'other'     =>  new \stdClass(),
                    'time'      =>  getLongTime(),
                );
                return $this->openFire->message('service', $toId, $content, 'batchusers');

            }
        }

//        $apiCtrl = new \Api\Controller\IndexController();
//        $userM = new UserModel();
//        $relationM = new Model('relation');
//        $relD = $relationM->where(array('uid'=>$uid, 'online'=>1))->field('sid')->select();
//        $phone = $userM->getPhoneByUid($uid);
//        if($relD)foreach($relD as $rel){
//            $apiCtrl->systemToUser(array('usrname'=>array($phone)), 27, 's'.$rel['sid']);
//        }
    }
}
