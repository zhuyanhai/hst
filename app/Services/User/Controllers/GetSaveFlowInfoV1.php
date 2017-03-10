<?php

namespace App\Services\User\Controllers;

use App\Services\ServiceAbstract;

/**
 * 获取用户节省流量信息
 *
 * 版本号：v1
 *
 * Class GetSaveFlowInfoV1
 * @package App\Services\User\Controllers;
 */
class GetSaveFlowInfoV1 extends ServiceAbstract
{
    /**
     * 校验请求参数
     *
     * true = 校验通过 false=校验不通过
     * @return boolean
     */
    public function paramsValidate()
    {
        return $this->_validate($this->params, [
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

        //todo 假数据
        return $this->response([
            'yestodaySaveFlow' => '13M',
            'totalSaveFlow' => '300M',
            'totalSaveMoney' => '50元',
        ]);

    }

}