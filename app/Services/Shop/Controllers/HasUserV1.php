<?php

namespace App\Services\Shop\Controllers;

use App\Services\ServiceAbstract;
use App\Services\Shop\Models\ShopModel;
use App\Services\Shop\Helpers\FormatShopInfo;

/**
 * 检测商户是否存在
 *
 * 版本号：v1
 *
 * Class HasUserV1
 * @package App\Services\Shop\Controllers;
 */
class HasUserV1 extends ServiceAbstract
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
        $shopModel = ShopModel::where('uid', $this->_params['userid'])->where('status', 1)->first();
        if ($shopModel) {
            return $this->response(['isHas' => 1]);
        } else {
            return $this->response(['isHas' => 0]);
        }
    }

}