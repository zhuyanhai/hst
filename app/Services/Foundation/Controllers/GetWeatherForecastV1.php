<?php

namespace App\Services\Foundation\Controllers;

use App\Services\ServiceAbstract;
use App\Services\Foundation\Models\WeatherForecastModel;

/**
 * 获取天气预报
 *
 * 版本号：v1
 *
 * Class GetWeatherForecastV1
 * @package App\Services\Foundation\Controllers
 */
class GetWeatherForecastV1 extends ServiceAbstract
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
            'isCheck'  => 'required|integer',
            'dates'    => 'required',
        ], [
            'isCheck.required' => '缺少isCheck参数',//isCheck=1 仅检查今天是否有天气预报 ＝0 获取今日天气预报数据
            'isCheck.integer' => '参数isCheck错误',
            'dates.required' => '缺少dates参数',
        ]);
    }

    /**
     * 服务必须实现的方法，因为调用服务会自动调用本方法
     *
     * @return array
     */
    public function run()
    {
        $dates = preg_replace('%-%i', '', $this->_params['dates']);

        if ($this->_params['isCheck'] == 1) {//仅检查今天是否有天气预报
            $weatherForecastModel = WeatherForecastModel::where('dates', $dates)->first(['id']);
            if ($weatherForecastModel) {
                return $this->response([
                    'isCrawler' => 1,
                ]);
            } else {
                return $this->response([
                    'isCrawler' => 0,
                ]);
            }
        } else {
            $weatherForecastModel = WeatherForecastModel::where('dates', $dates)->get(['id', 'type', 'fishery_name', 'contents']);
            return $this->response($weatherForecastModel->toArray());
        }

    }
}
