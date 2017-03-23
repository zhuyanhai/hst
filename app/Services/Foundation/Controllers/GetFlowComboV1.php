<?php

namespace App\Services\Foundation\Controllers;

use App\Services\ServiceAbstract;
use App\Services\Foundation\Models\FlowModel;

/**
 * 获取单个流量包套餐信息
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
        return $this->_validate($this->_params, [
            'id' => 'required|integer',
        ], [
            'id.required' => '参数id丢失',
            'id.integer' => '参数id丢失',
        ]);
    }

    /**
     * 服务必须实现的方法，因为调用服务会自动调用本方法
     *
     * @return array
     */
    public function run()
    {
        $flowModel = FlowModel::where('id', $this->_params['id'])->first();
        if (!$flowModel) {
            return $this->error('获取单个流量宝套餐信息失败');
        }
        return $this->response($flowModel->toArray());
    }
}
