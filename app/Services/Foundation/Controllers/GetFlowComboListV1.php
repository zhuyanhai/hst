<?php

namespace App\Services\Foundation\Controllers;

use App\Services\ServiceAbstract;
use App\Services\Foundation\Models\FlowModel;

/**
 * 获取流量包套餐列表
 *
 * 版本号：v1
 *
 * Class GetFlowComboListV1
 * @package App\Services\Foundation\Controllers
 */
class GetFlowComboListV1 extends ServiceAbstract
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
            'mode'   => 'required|string',
        ], [
            'mode.required'  => '参数mode丢失',
            'mode.string'  => '参数mode丢失',
        ]);
    }

    private $_modeMaps = [
        'list' => '_getList',
        'ids'  => '_getByIds',
    ];

    /**
     * 服务必须实现的方法，因为调用服务会自动调用本方法
     *
     * @return array
     */
    public function run()
    {
        if (!isset($this->_modeMaps[$this->_params['mode']])) {
            $this->error('获取失败');
        }

        $method = $this->_modeMaps[$this->_params['mode']];
        return $this->$method();
    }

    /**
     * 获取流量包套餐列表
     *
     * @return array
     */
    private function _getList()
    {
        $flowModelList = FlowModel::where('id', '<=', 9)->get();
        if ($flowModelList->count() > 0) {
            return $this->response($flowModelList->toArray());
        }
        return $this->response();
    }

    /**
     * 获取流量包套餐列表 - 根据指定的id数组
     *
     * @return array
     */
    private function _getByIds()
    {
        $flowModelList = FlowModel::findMany($this->_params['ids']);
        if ($flowModelList->count() > 0) {
            return $this->response($flowModelList->toArray());
        }
        return $this->response();
    }
}
