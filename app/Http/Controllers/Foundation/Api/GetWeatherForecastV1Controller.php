<?php

namespace App\Http\Controllers\Foundation\Api;

use App\Http\Controllers\ApiController;

/**
 * 获取天气预报接口
 *
 * 版本号：v1
 *
 * Class GetWeatherForecastV1Controller
 * @package App\Http\Controllers\Foundation\Api
 */
class GetWeatherForecastV1Controller extends ApiController
{
    /**
     * 校验请求参数
     *
     * true = 校验通过 false=校验不通过
     * @return boolean
     */
    protected function paramsValidate()
    {
        return true;
    }

    /**
     * API 接口对应的执行方法
     *
     * @return \App\Http\Controllers\response
     */
    public function run()
    {
        if (!isset($this->_params['dates'])) {
            $dates = date('Ymd');
        } else {
            $this->_validate($this->_params, [
                'dates' => 'date_format:yyyy-mm-dd',
            ], [
                'dates.date_format' => '日期参数格式错误，正确格式：2017-01-01'
            ]);

            $dates = preg_replace('%-%i', '', $this->_params['dates']);
        }

        $result = callService('foundation.GetWeatherForecastV1', ['isCheck' => 0, 'dates' => $dates]);

        if ($result['code'] != 0) {
            $this->error($result['msg']);
        }

        return $this->response($result['data']);
    }

}
