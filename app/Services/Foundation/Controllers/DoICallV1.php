<?php

namespace App\Services\Foundation\Controllers;

use App\Services\ServiceAbstract;
use App\Services\Foundation\Models\Aps\Aps_UserModel;
use App\Services\Foundation\Models\Aps\Aps_VFieldModel;
use App\Services\Foundation\Models\Aps\Aps_FieldAccountModel;

/**
 * 与 voip 通信
 *
 * 第二版再做，1.1.0不做
 *
 * 版本号：v1
 *
 * Class DoICallV1
 * @package App\Services\Foundation\Controllers
 */
class DoICallV1 extends ServiceAbstract
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
            'action' => 'required|string',//动作，要求做什么通信
        ], [
            'action.required' => '缺少action参数',
            'action.string' => '参数action错误',
        ]);
    }

    //测试配置
    private $_cfg = [
        'ip'   => '124.206.31.236',
        'port' => 6000,
    ];

    private $_actionMaps = [
        'registerUser' => '_registerUser',
        'deleteUser'   => '_deleteUser',
    ];

    /**
     * 服务必须实现的方法，因为调用服务会自动调用本方法
     *
     * @return array
     */
    public function run()
    {
        if (!isset($this->_actionMaps[$this->_params['action']])) {
            $this->error('获取失败');
        }

        $method = $this->_actionMaps[$this->_params['action']];
        return $this->$method();
    }

    /**
     * 注册到 voip
     *
     * @return array
     */
    private function _registerUser()
    {
        $phone    = $this->_params['data']['phone'];
        $password = $this->_getUserPwd($this->_params['data']['password']);
        $pv       = $this->_params['data']['systemType'];
        $code     = md5($phone . config('site.appCode'));
        $url 	  = config('site.iCallUrl') . '?phone='.$phone.'&pwd='.$password.'&agent_id=1&sign='.$code.'&v=3&pv='.$pv;
        $result = json_decode(file_get_contents($url),true);
        return $this->response($result);
    }

    /**
     * 删除 voip 用户
     *
     * @return array
     */
    private function _deleteUser()
    {
        $phone = $this->_params['data']['phone'];

        $model = Aps_UserModel::where('long_name', $phone)->first(['field_id']);

        DB::beginTransaction();

        $flag1 = Aps_FieldAccountModel::where('field_id', $model->field_id)->delete();
        $flag2 = Aps_VFieldModel::where('field_id', $model->field_id)->delete();
        $flag3 = Aps_UserModel::where('field_id', $model->field_id)->delete();

        if ($flag1 && $flag2 && $flag3) {
            DB::commit();
            return $this->response();
        } else {
            DB::rollBack();
            $this->error('voip删除用户操作失败');
        }
    }

    /**
     * 从icall拿过来的，移位加密算法
     *
     * @param string $pwd
     * @return string
     */
    private function _getUserPwd($pwd) {
        $data = strval($pwd);
        if(is_null($data) || strlen($data)==0) {
            return '';
        }

        $dataLen=strlen($data);
        if($dataLen>12) {
            $dataLen=20;
        }

        $ret = '';
        for($i=0; $i < $dataLen; $i++) {
            $value = ord($data[$i]);
            if(($value > 0x60) && ($value < 0x7B)) {
                $value = $value - 0x20;
                $value = 0x5A - $value + 0x41;
            } else if(($value > 0x40) && ($value < 0x5B)) {
                $value = $value + 0x20;
                $value = 0x7A - $value + 0x61;
            } else if(($value >= 0x30) && ($value <= 0x34)) {
                $value = $value + 0x05;
            } else if(($value >= 0x35) && ($value <= 0x39)) {
                $value = $value - 0x05;
            }
            $ret .= chr($value);
        }
        return $ret;
    }

}
