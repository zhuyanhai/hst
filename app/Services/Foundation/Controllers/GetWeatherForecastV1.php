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
            'isPrev'   => 'required|integer',
        ], [
            'isCheck.required' => '缺少isCheck参数',//isCheck=1 仅检查今天是否有天气预报 ＝0 获取今日天气预报数据
            'isCheck.integer' => '参数isCheck错误',
            'dates.required' => '缺少dates参数',
            'isPrev.required' => '缺少isPrev参数',//isPrev=1 指定日期没有就获取上一次的 ＝0 指定日期没有就忽略
            'isPrev.integer' => '参数isPrev错误',
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
            if ($weatherForecastModel->count() <= 0) {
                if ($this->_params['isPrev'] > 0) {
                    $weatherForecastModel = WeatherForecastModel::orderBy('id', 'desc')->first(['dates']);
                    if (!$weatherForecastModel) {
                        $this->error('无可用天气预报');
                    }
                    $dates = $weatherForecastModel->dates;
                    $weatherForecastModel = WeatherForecastModel::where('dates', $dates)->get(['id', 'type', 'fishery_name', 'contents']);
                } else {
                    $this->error('无可用天气预报');
                }
            }
            $list = $weatherForecastModel->toArray();
            foreach ($list as &$v) {
                $v['contents'] = preg_replace("%\\r%", '', $v['contents']);
            }

            $year  = mb_substr($dates, 0, 4);
            $month = mb_substr($dates, 4, 2);
            $day   = mb_substr($dates, 6, 2);
            return $this->response(['date' => mktime(0,0,0,$month, $day, $year).'000', 'weatherList' => $list]);
        }

    }
}
