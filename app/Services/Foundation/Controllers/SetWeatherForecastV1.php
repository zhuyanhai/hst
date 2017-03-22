<?php

namespace App\Services\Foundation\Controllers;

use Illuminate\Support\Facades\DB;
use App\Services\ServiceAbstract;
use App\Services\Foundation\Models\WeatherForecastModel;

/**
 * 设置天气预报
 *
 * 版本号：v1
 *
 * Class SetWeatherForecastV1
 * @package App\Services\Foundation\Controllers
 */
class SetWeatherForecastV1 extends ServiceAbstract
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
            'contents' => 'required',
            'dates'    => 'required',
        ], [
            'contents.required' => '缺少contents参数',
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

        $overview = $this->_params['contents'][2].$this->_params['contents'][3].$this->_params['contents'][4];
        $overview = trim($overview);
        $overview = trim($overview, "\n");

        DB::beginTransaction();
        $weatherForecastModel = new WeatherForecastModel();
        $weatherForecastModel->dates = $dates;
        $weatherForecastModel->type = 1;
        $weatherForecastModel->fishery_name = '';
        $weatherForecastModel->contents = $overview;
        $weatherForecastModel->created_at = time();
        try {
            if ($weatherForecastModel->save()) {
                unset($weatherForecastModel);
                $isCommit = 1;
                foreach ($this->_params['contents'] as $index=>$val) {
                    if ($index > 4) {
                        $valArray = explode('：', $val);
                        $fisheryName = trim($valArray[0]);
                        $fisheryName = trim($fisheryName, "\n");
                        unset($valArray[0]);
                        $contents = implode('：', $valArray);
                        $contents = trim($contents, "\n");

                        $weatherForecastModel = new WeatherForecastModel();
                        $weatherForecastModel->dates = $dates;
                        $weatherForecastModel->type = 2;
                        $weatherForecastModel->fishery_name = $fisheryName;
                        $weatherForecastModel->contents = $contents;
                        $weatherForecastModel->created_at = time();
                        if (!$weatherForecastModel->save()) {
                            $isCommit = 0;
                            break;
                        }
                        unset($weatherForecastModel);
                    }
                }
                if ($isCommit) {
                    DB::commit();
                }
            }
        } catch(\Exception $e) {
            //todo
        }

        return $this->response();
    }
}
