<?php

namespace App\Services\User\Controllers;

use Illuminate\Support\Facades\DB;
use App\Services\ServiceAbstract;
use App\Services\User\Models\AccountModel;
use App\Services\User\Models\AccountLogModel;

/**
 * 设置单个账户信息
 *
 * 版本号：v1
 *
 * Class SetAccountV1
 * @package App\Services\User\Controllers;
 */
class SetAccountV1 extends ServiceAbstract
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
        DB::beginTransaction();
        try {
            $accountModel = AccountModel::where('uid', $this->_params['userid'])->first();
            if (!$accountModel) {
                $this->error('用户不存在');
            }

            foreach ($this->_params['setInfo'] as $field=>$val) {
                $accountModel->$field = $val;
            }

            if ($accountModel->save()) {

                if (!$this->_saveLog($this->_params['setLogInfo'])) {
                    DB::rollback();
                    $this->error('设置用户账户日志失败');
                }

                DB::commit();

            } else {
                $this->error('设置用户账户信息失败');
            }

        } catch(\Exception $e) {
            $this->error('设置用户账户信息失败');
        }

        return $this->response();
    }

    /**
     * 记录日志
     *
     * @param array $setLogInfo
     * @return bool
     */
    private function _saveLog($setLogInfo)
    {
        $accountLogModel = new AccountLogModel();

        foreach ($setLogInfo as $field=>$val) {
            $accountLogModel->$field = $val;
        }

        if ($accountLogModel->save()) {
            return true;
        }

        return false;
    }

}