<?php

namespace App\Services\Foundation\Controllers;

use App\Services\ServiceAbstract;
use App\Services\Foundation\Models\FlowModel;

/**
 * 获取流量包套餐列表
 *
 * 版本号：v1
 *
 * Class GetFlowComboV1
 * @package App\Services\Foundation\Controllers
 */
class GetFlowComboV1 extends ServiceAbstract
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

        $flowModelList = FlowModel::where('id', '<=', 9)->get();
        if ($flowModelList->count() > 0) {
            return $this->response($flowModelList->toArray());
        }
        return $this->response();
    }
}
