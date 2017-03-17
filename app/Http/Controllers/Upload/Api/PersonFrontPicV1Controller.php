<?php

namespace App\Http\Controllers\Upload\Api;


use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Storage;

/**
 * 上传身份证照片 正面
 *
 * Class PersonFrontPicV1Controller
 * @package App\Http\Controllers\Upload\Api
 */
class PersonFrontPicV1Controller extends ApiController
{
    /**
     * 校验请求参数
     *
     * true = 校验通过 false=校验不通过
     * @return boolean
     */
    protected function paramsValidate()
    {
        return $this->_validate($this->_params, [
            'uid'  => 'required|integer',
        ], [
            'uid.required'  => '参数错误',
            'uid.integer' => '参数错误',
        ]);
    }

    /**
     * API 接口对应的执行方法
     *
     * @param Request $request
     * @return \App\Http\Controllers\response
     */
    public function run(Request $request)
    {
        if ($request->hasFile('Filedata')) {
            $uid = $this->_params['uid'];
            $uidMd5 = md5($uid);
            $dir1 = substr($uidMd5, 0, 2);
            $dir2 = substr($uidMd5, 2, 2);
            $dir  = '/user_id_card/'.$dir1.'/'.$dir2.'/'.$uid;
            $filename = $uid.'_front.jpg';
            $returnPath = 'http://hst.bxshare.cn/storage'.$dir.'/'.$filename.'?v='.time();

            Storage::makeDirectory($dir);

            $path = $request->file('Filedata')->storeAs('/public' . $dir, $uid.'_front.jpg');


            return $this->response([
                'path' => $returnPath
            ]);
        } else {
            $this->error('未有文件被上传');
        }
    }

}