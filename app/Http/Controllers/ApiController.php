<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiException;
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Validator;

abstract class ApiController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * 请求参数
     *
     * @var array
     */
    protected $_params = [];

    /**
     * 请求参数 header
     *
     * @var array
     */
    protected $_headers = [];

    /**
     * appId
     *
     * app请求，非H5
     *
     * @var string
     */
    protected $appId;

    /**
     * appSecret
     *
     * app请求，非H5
     *
     * @var string
     */
    protected $appSecret;

    /**
     * 返回状态码
     *
     * @var int
     */
    protected $returnCode = 0;

    /**
     * 返回错误信息
     *
     * @var string
     */
    protected $returnMsg = '';

    /**
     * 是否是h5请求
     *
     * @var bool
     */
    protected $isH5Request = false;

    /**
     * 定义接口必须登录才可以被访问
     *
     * @var bool true＝必须登录 false＝可以不登陆就访问
     */
    protected $foreLogin = true;

    /**
     * 当前登录用户
     *
     * @var array
     */
    protected $loginUserInfo = null;


    /**
     * 错误码
     *
     * @var array
     */
    protected $_errCodes = [
        // 系统码
        '0' => '成功',
        '400' => '未知错误',
        '403' => '无此权限',
        '500' => '服务器异常',

        //指定有意义的错误段 4000 - 4999
        '4000' => '您处于未登陆状态，请先登录！',

        // 公共错误码
        '1001' => '[appId]缺失',
        '1002' => '[appId]不存在或无权限',
        '1003' => '[method]缺失',
        '1004' => '[format]错误',
        '1005' => '[sign_method]错误',
        '1006' => '[sign]缺失',
        '1007' => '[sign]签名错误',
        '1008' => '[method]方法不存在',
        '1009' => 'run方法不存在，请联系管理员',
        '1010' => '[nonce]缺失',
        '1011' => '[nonce]必须为字符串',
        '1012' => '[nonce]长度必须为1-32位',
        '1013' => '[version]缺失',
        '1014' => '[version]必须为字符串',

        '9001' => '[HST_BUNDLEID]缺失',
        '9002' => '[HST_SYSTEM]缺失',
        '9003' => '[HST_DEVICE_MAC]缺失',
        '9004' => '[HST_PACKAGE]缺失',
        '9005' => '[HST_VERSION]缺失',
        '9006' => '[HST_APPID]缺失',
    ];

    /**
     * ApiController constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->_params  = $request->all();
        $this->_headers = $request->header();
        //if (isset($this->_params['jsoncallback'])) {
        if (isset($this->_params['p'])) {//临时
            $this->isH5Request = true;
        }

        //请求头校验
        if (!$this->isH5Request) {
            $this->headerValidate();
        }

        //检测是否必须登录
        if ($this->foreLogin && !$this->isH5Request) {//必须登录
            if (!isset($this->_headers['hst-token'])) {
                return $this->error('', 4000)->response();
            }
            $result = callService('user.checkLoginV1', ['token'=>$this->_headers['hst-token'][0]]);
            if ($result['code'] != 0) {
                return $this->error('', 4000);
            } else {
                $this->loginUserInfo = $result['data'];
            }
        }

        //请求参数校验
        $this->paramsValidate();
    }

    /**
     * 请求头校验
     *
     * @return bool
     */
    protected function headerValidate()
    {
        if (isset($this->_headers['hst-system']) && !in_array($this->_headers['hst-system'], ['android','iphone'])) {
            $this->isH5Request = true;
            return true;
        }

        //header校验规则
        $rulesOfHeader = [
            'hst-bundleid' => 'required',
            'hst-system' => 'required',
            'hst-devicemac' => 'required',
            'hst-package' => 'required',
            'hst-version' => 'required',
            'hst-appid' => 'required',
        ];
        //header校验错误信息对照表
        $messagesOfHeader = [
            'hst-bundleid.required' => '9001',
            'hst-system.required' => '9002',
            'hst-devicemac.required' => '9003',
            'hst-package.required' => '9004',
            'hst-version.required' => '9005',
            'hst-version.required' => '9005',
            'hst-appid.required' => '9006',
        ];

        $vResultOfHeader = $this->_validate($this->_headers, $rulesOfHeader, $messagesOfHeader);
        if (!$vResultOfHeader) {
            return false;
        }

        //赋值对象
        $this->appId = $this->_headers['hst-appid'][0];

        //appid校验
        //$appModel = AppModel::where('app_id', $this->appId)->first();
        //if (! $appModel)  {
        //    return $this->error('', 1002)->response();
        //}
        //因为秘钥有好几种，临时使用android
        //$this->appSecret = $appModel->appSecret_andorid;

        // C. 校验签名
//        $signRes = $this->checkSign($this->params);
//        if (! $signRes || ! $signRes['status']) {
//            return $this->response(['status' => false, 'code' => $signRes['code']]);
//        }

    }

    /**
     * 输出结果
     *
     * @param  array $result 结果
     * @return response
     */
    protected function response(array $result = array(), array $cookies = array(), array $globalData = array())
    {
        $return = [
            'data' => [
                'localData' => new \stdClass(),
                'globalData' => new \stdClass(),
            ],
            'cookies' => new \stdClass(),
        ];

        if (!empty($result)) {
            $return['data']['localData'] = $result;
        }
        if (!empty($cookies)) {
            $return['cookies'] = json_decode(json_encode($cookies));
        }
        if (!empty($globalData)) {
            $return['data']['globalData'] = $globalData;
        }

        //todo 获取全局
        $return['data']['globalData'] = [
            //流量单位 M 兆
            'currentTotalFlow' => 100,//当前总流量
            'currentResidualFlow' => 10,//当前剩余流量
            'todayFlow' => 10,//今日已用流量
            'todaySavedFlow' => 5,//今日已省流量
        ];

        if ($this->isH5Request && isset($this->params['jsoncallback'])) {//h5
            $responseObj = response()->jsonp($this->params['jsoncallback'], $return['data']);
            if (!empty($cookies)) {
                foreach ($cookies as $cookie) {
                    $responseObj->cookie($cookie);
                }
            }
            return $responseObj;
        }

        return response()->json([
            'state' => [
                "code" => 0,// 0=成功 非0=失败
                "msg" => '',//失败理由
            ],
            'data' => $return['data'],
            'cookies' => $return['cookies'],
        ], 200, ['Access-Control-Allow-Credentials'=>'true', 'Access-Control-Allow-Origin'=>'*']);

        return false;
    }

    /**
     * 设置错误信息
     *
     * @param string $errorMsg 错误描述
     * @param int $code 错误状态码
     * @return bool
     */
    protected function error($errorMsg = '', $code = 400)
    {
        if (empty($errorMsg)) {
            if (!isset($this->_errCodes[$code])) {
                $code = '400';
            }
            $errorMsg = $this->_errCodes[$code];
        }
        throw new ApiException($errorMsg, $code);
    }

    /**
     * 根据给定的规则校验请求参数
     *
     * @param  array  $args
     * @param  array  $rules
     * @param  array  $messages
     * @return boolean
     */
    protected function _validate(array $args, array $rules, array $messages = [])
    {
        $vResult = Validator::make($args, $rules, $messages);

        if ($vResult->fails()) {
            return $this->error($vResult->errors()->first());
        }

        return true;
    }

    /**
     * 校验签名
     *
     * @param  [type] $params [description]
     * @return array
     */
    protected function checkSign($params)
    {
        $sign = array_key_exists('sign', $params) ? $params['sign'] : '';

        if (empty($sign))
            return array('status' => false, 'code' => '1006');

        unset($params['sign']);

        if ($sign != $this->generateSign($params))
            return array('status' => false, 'code' => '1007');

        return array('status' => true, 'code' => '200');
    }

    /**
     * 生成签名
     *
     * @param  array $params 待校验签名参数
     * @return string|false
     */
    protected function generateSign($params)
    {
        if ($this->signMethod == 'md5')
            return $this->generateMd5Sign($params);

        return false;
    }

    /**
     * md5方式签名
     *
     * @param  array $params 待签名参数
     * @return string
     */
    protected function generateMd5Sign($params)
    {
        ksort($params);

        $tmps = array();
        foreach ($params as $k => $v) {
            $tmps[] = $k . $v;
        }

        $string = $this->appSecret . implode('', $tmps) . $this->appSecret;

        return strtoupper(md5($string));
    }

    /**
     * 请求参数检测
     *
     * @return mixed
     */
    abstract protected function paramsValidate();

}
