<?php

namespace App\Services\User\Controllers;

use App\Services\ServiceAbstract;
use App\Services\User\Models\UserModel;
use App\Services\User\Helpers\LoginToken;
use App\Services\User\Helpers\User;

/**
 * 用户登录服务
 *
 * 版本号：v1
 *
 * Class DoLoginV1
 * @package App\Services\User\Controllers;
 */
class DoLoginV1 extends ServiceAbstract
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
            'account'  => 'required',
            'password' => 'required',
        ], [
            'account.required'  => '请输入手机号',
            'password.required' => '请输入密码',
        ]);
    }

    /**
     * 服务必须实现的方法，因为调用服务会自动调用本方法
     *
     * @return array
     */
    public function run()
    {
        $userModel = UserModel::where('phone', $this->_params['account'])->first();
        if (!$userModel) {
            $this->error('该帐号不存在');
        }
        if (intval($userModel->frozen) !== 1) {
            if ($userModel->password == md5($this->_params['password'])) {

                //VOIP
                //$iModel = new \Common\Model\IcallModel();
                //$iUid = $iModel->phoneGetUid($phone);
                //$udata = $this->user($userModel->uid);
                //$udata['data']['iuid'] = $iUid;

                $result = callService('user.getInfoV1', ['userid' => $userModel->uid]);

                if (intval($result['code']) != 0) {
                    $this->error($result['msg']);
                }

                //临时
                $result['data']['iuid'] = 0;

                //用户登录token
                $result['data']['token'] = LoginToken::build($userModel->uid, $userModel->phone, $userModel->password, $userModel->createtime);

                //设置用户信息到openVpn
                User::setInfoToOpenVpn($userModel->uid, $result['data']['token'], $userModel->traffic_patterns, $userModel->allow_external_updates, $this->_params['_apiHeaders']);

                //是否允许登陆，允许1＝代表审核通过 不允许0＝代表审核没有通过
                if ($result['data']['isRegisterCheck'] < 2) {//<2 审核未通过 或 待审核
                    if($result['data']['isRegisterCheck'] < 1) {
                        $checkStatus = 0;//审核不通过
                    } elseif ($result['data']['personFrontPic'] == '' || $result['data']['personBackPic'] == '') {
                        $checkStatus = 1;//待审核
                    } else {
                        $checkStatus = 2;//审核中
                    }
                    return $this->response([
                        'uid'         => $result['data']['uid'],
                        'token'       => $result['data']['token'],
                        'checkStatus' => $checkStatus,
                    ], $result['cookies']);
                }

                return $this->response($result['data'], $result['cookies']);
            }else {
                $this->error('密码错误');
            }
        } else {
            $this->error('该用户已被冻结');
        }
    }
}
