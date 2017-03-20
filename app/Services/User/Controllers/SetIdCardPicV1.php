<?php

namespace App\Services\User\Controllers;

use App\Services\ServiceAbstract;
use App\Services\User\Helpers\User;

/**
 * 设置用户身份证照片（正反面两张），实名认证使用
 *
 * 版本号：v1
 *
 * Class SetTrafficPatternsV1
 * @package App\Services\User\Controllers;
 */
class SetIdCardPicV1 extends ServiceAbstract
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
            'personFrontPic' => 'required',//身份证正面图片
            'personBackPic' => 'required',//身份证背面图片
        ], [
            'userid.required'  => '参数丢失',
            'personFrontPic.required' => '请上传身份证正面照片',
            'personBackPic.required' => '请上传身份证背面照片',
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
            return $this->error('用户不存在');
        }
        $userModel->person_front_pic = $this->_params['personFrontPic'];
        $userModel->person_back_pic = $this->_params['personBackPic'];
        $userModel->save();
        return $this->response();
    }

}
