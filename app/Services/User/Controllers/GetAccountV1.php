<?php

namespace App\Services\User\Controllers;

use App\Services\ServiceAbstract;
use App\Services\User\Models\AccountModel;

/**
 * 获取单个账户信息
 *
 * 版本号：v1
 *
 * Class GetAccountV1
 * @package App\Services\User\Controllers;
 */
class GetAccountV1 extends ServiceAbstract
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
        $accountModel = AccountModel::where('uid', $this->_params['userid'])->first();
        if (!$accountModel) {
            $this->error('用户不存在');
        }

        return $this->response($accountModel->toArray());

    }

}