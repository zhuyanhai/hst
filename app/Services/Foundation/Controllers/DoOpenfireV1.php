<?php

namespace App\Services\Foundation\Controllers;

use App\Services\ServiceAbstract;
use App\Libraries\Hst\Openfire;

/**
 * 使用 xmpp openfire 通信
 *
 * 与路由器通信
 * 与手机app通信
 *
 * 版本号：v1
 *
 * Class DoOpenfireV1
 * @package App\Services\Foundation\Controllers
 */
class DoOpenfireV1 extends ServiceAbstract
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
            'who'    => 'required|string',//与谁通信 app(openfire转发到指定用户) | router(openfire转发到指定船) | openfire（仅到达openfire）
            'action' => 'required|string',//动作，要求做什么通信
        ], [
            'who.required' => '缺少who参数',
            'who.string' => '参数who错误',
            'action.required' => '缺少action参数',
            'action.string' => '参数action错误',
        ]);
    }

    private $_actionMaps = [
        'registerUser'   => '_registerUserOfOpenfire',
        'deleteUser'     => '_deleteUserOfOpenfire',
        'editUserPasswd' => '_editUserPasswdOfOpenfire',
    ];

    /**
     * 服务必须实现的方法，因为调用服务会自动调用本方法
     *
     * @return array
     */
    public function run()
    {
        if (!in_array($this->_params['who'], ['openfire', 'router', 'app'])) {
            $this->error('参数who错误');
        }

        if (!isset($this->_actionMaps[$this->_params['action']])) {
            $this->error('获取失败');
        }

        $method = $this->_actionMaps[$this->_params['action']];
        $result = $this->$method();
        return $this->response($result);
    }

    /**
     * 注册到openfire
     *
     * @return array 1-注册成功 0-注册失败 -1-用户已注册
     */
    private function _registerUserOfOpenfire()
    {
        $username = $this->_params['data']['username'];
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

    /**
     * 删除注册到openfire的用户
     *
     * @return array 1-删除成功 0-删除失败 -1-用户不存在
     */
    private function _deleteUserOfOpenfire()
    {
        $username = $this->_params['data']['username'];
        $url 	  = '/plugins/userService/userservice';

        $result = Openfire::doRequest($url, [
            'type'     => 'delete',
            'username' => $username,
        ]);

        if (preg_match('/ok/', $result)) {
            $response = 1;
        } else if (preg_match('/UserNotFoundException/', $result)) {
            $response = -1;
        } else {
            $response = 0;
        }

        return ['result' => $response];
    }

    /**
     * 编辑openfire的用户密码
     *
     * @return array 1-修改成功 0-修改失败 -1-用户不存在
     */
    private function _editUserPasswdOfOpenfire()
    {
        $username = $this->_params['data']['username'];
        $password = $this->_params['data']['password'];
        $url 	  = '/plugins/userService/userservice';

        $result = Openfire::doRequest($url, [
            'type'     => 'update',
            'username' => $username,
            'password' => $password,
        ]);

        if (preg_match('/ok/', $result)) {
            $response = 1;
        } else if (preg_match('/UserNotFoundException/', $result)) {
            $response = -1;
        } else {
            $response = 0;
        }

        return ['result' => $response];
    }
}
