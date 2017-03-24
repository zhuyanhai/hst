<?php

namespace App\Services\Foundation\Controllers;

use App\Services\ServiceAbstract;

/**
 * 与 voip 通信
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
        $pwd = get_user_pwd($pwd);
        $pv = $pv ? $pv : 'android';
        $code = $code = md5($phone.C('APP_CODE'));
        $url = C('i_regist').'?phone='.$phone.'&pwd='.$pwd.'&agent_id=1&sign='.$code.'&v=3&pv='.$pv;
//    return (array)(json_decode(sendHttp($url)));
        return json_decode(file_get_contents($url),true);

        $phone    = $this->_params['data']['phone'];
        $password = $this->_params['data']['password'];
        $url 	  = '/plugins/userService/userservice';

        $result = Openfire::doRequest($url, [
            'type'     => 'add',
            'username' => $username,
            'password' => $password,
        ]);

        file_put_contents('/tmp/aa', $result.PHP_EOL, 8);

        if (preg_match('/ok/', $result)) {
            $response = 1;
        } else if (preg_match('/UserAlreadyExistsException/', $result)) {
            $response = -1;
        } else {
            $response = 0;
        }

        return ['result' => $response];
    }

}
