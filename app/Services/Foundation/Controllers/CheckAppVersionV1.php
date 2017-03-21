<?php

namespace App\Services\Foundation\Controllers;

use App\Services\ServiceAbstract;
use App\Services\Foundation\Models\AppsVersionModel;

/**
 * 校验app是否需要升级
 *
 * 版本号：v1
 *
 * Class CheckAppVersionV1
 * @package App\Services\Foundation\Controllers
 */
class CheckAppVersionV1 extends ServiceAbstract
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
            'appMark'     => 'required',
            'systemType'  => 'required',
            'packageName' => 'required',
            'version'     => 'required',
        ], [
            'appMark.required' => '缺少appMark参数',
            'systemType.required' => '缺少system参数',
            'packageName.required' => '缺少packageName参数',
            'version.required' => '缺少version参数',
        ]);
    }

    /**
     * 服务必须实现的方法，因为调用服务会自动调用本方法
     *
     * @return array
     */
    public function run()
    {
        $currentVersion = explode('.', $this->_params['version']);

        $force = AppsVersionModel::where('system_type', $this->_params['systemType'])
                        ->where('app_mark', $this->_params['appMark'])
                        ->where('status', 0)
                        ->where('package_name', $this->_params['packageName'])
                        ->where('update_status', 2)
                        ->orderBy('id', 'desc')
                        ->first();

        $checkFun = function($model, $currentVersion, $isForce)
        {
            $appVersion = explode('.', $model->app_version);
            $isUpdate = 0;
            if ($appVersion[0] > $currentVersion[0]) {
                $isUpdate = 1;
            } elseif($appVersion[0] == $currentVersion[0] && $appVersion[1] > $currentVersion[1]) {
                $isUpdate = 1;
            } elseif($appVersion[0] == $currentVersion[0] && $appVersion[1] == $currentVersion[1] && $appVersion[2] > $currentVersion[2]) {
                $isUpdate = 1;
            }
            if ($isUpdate) {
                return [
                    'isUpdate'    => 1,//1=需要更新 0=不需要更新
                    'isForce'     => $isForce,//1=强制更新 0=正常更新
                    'version'     => $model->app_version,//最新版本号
                    'title'       => $model->title,//提示标题
                    'contents'    => $model->contents,//提示文字
                    'downloadUrl' => $model->download_url,//包url
                ];
            }

            return ['isUpdate' => 0];
        };


        if ($force) {//强制更新
            $result = $checkFun($force, $currentVersion, 1);
            if ($result['isUpdate'] > 0) {
                return $this->response($result);
            }
        }

        $normal = AppsVersionModel::where('system_type', $this->_params['systemType'])
            ->where('app_mark', $this->_params['appMark'])
            ->where('status', 0)
            ->where('package_name', $this->_params['packageName'])
            ->where('update_status', 1)
            ->orderBy('id', 'desc')
            ->first();

        if ($normal) {//正常更新
            $result = $checkFun($normal, $currentVersion, 0);
            if ($result['isUpdate'] > 0) {
                return $this->response($result);
            }
        }

        return $this->response([
            'isUpdate' => 0,
        ]);
    }
}
