<?php

namespace App\Services\Foundation\Controllers;

use App\Services\ServiceAbstract;

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
        'registerUser'   => '_registerUser',
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
        $result = $this->$method();
        return $this->response($result);
    }

    /**
     * 注册到 voip
     *
     * @return array 1-注册成功 0-注册失败 -1-用户已注册
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
