<?php

namespace App\Services\User\Controllers;

use App\Services\ServiceAbstract;
use App\Services\User\Models\UserFeedbackModel;

/**
 * 设置用户反馈
 *
 * 版本号：v1
 *
 * Class SetFeedBackV1
 * @package App\Services\User\Controllers;
 */
class SetFeedBackV1 extends ServiceAbstract
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
            'userid'      => 'required',
            'contentType' => 'required',
            'contents'    => 'required',

        ], [
            'userid' => 'required',
            'contentType.required' => '参数丢失',
            'contents.required' => '参数丢失',
        ]);
    }

    /**
     * 服务必须实现的方法，因为调用服务会自动调用本方法
     *
     * @return array
     */
    public function run()
    {
        //管理员i，后续写成配置
        $adminUserid = 6780;

        $userFeedbackModel = new UserFeedbackModel();
        $userFeedbackModel->userid = $this->_params['userid'];
        $userFeedbackModel->admin_userid = $adminUserid;
        $userFeedbackModel->is_admin_send = 0;
        $userFeedbackModel->content_type = $this->_params['contentType'];
        $userFeedbackModel->contents = $this->_params['contents'];
        $userFeedbackModel->created_at = time();
        $userFeedbackModel->save();

        return $this->response();
    }

}
