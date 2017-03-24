<?php
/**
 * openfire 交互类
 *
 * 通过userService插件的http方式与openfire通讯
 */

namespace App\Libraries\Hst;

use Illuminate\Support\Facades\Log;

class Openfire
{
    const OP_URL = 'http://127.0.0.1:9090'; //openfire 访问地址
    const OP_SECRET = "aaaaa";
    const OP_AS  = 'beautyas';  //小助手id

    /**
     * curl post 方法
     *
     * @param unknown $url
     * @param unknown $string
     * @return mixed
     */
    private static function _curlPost($url, $string)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url.'?'.$string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_POST, 1);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $string);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    /**
     * 向 openfire 通信
     *
     * @param $url
     * @param $params
     * @return mixed
     */
    public static function doRequest($url, $params)
    {
        $url = self::OP_URL . $url;
        $paramsStr = http_build_query($params);
        if (!empty($paramsStr)) {
            $paramsStr = 'secret=' . self::OP_SECRET . '&' . $paramsStr;
        } else {
            $paramsStr = 'secret=' . self::OP_SECRET;
        }

        $flag = microtime(true) * 10000;

        if (config('app.debug')) {
            Log::debug($flag.' -request = ' . $url.'?'.$paramsStr);
        }

        $result = self::_curlPost($url, $paramsStr);

        if (config('app.debug')) {
            Log::debug($flag.' -result = ' . $result);
        }

        return $result;
    }

    /**
     * 发送消息
     * @param string $from	发送者
     * @param string $to	接收者
     * @param array $body	消息体
     * @param string $action 类型 个人:person 所有人:allusers 所有在线用户:allonlineusers 指定用户:batchusers
     */
    public function message($from, $to, $body, $action='batchusers')
    {
        header("Content-Type:text/html; charset=utf-8");
        $url 	= $this->OP_URL . '/plugins/sendmsg/sendservlet';
        $param 	= 'from='.$from.'&to='.$to.'&body='.urlencode(json_encode($body)).'&action='.$action;

        if (config('app.debug')) {
            Log::debug("message = " . $url.'?'.$param);
        }

        $result = self::curlPost($url, $param);

        if (config('app.debug')) {
            Log::debug("message-result = " . $url.'?'.$param);
        }

        if (preg_match('/ok/', $result)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 通知
     *
     * @param unknown $from 发送者
     * @param unknown $to	接收者
     * @param number $type	消息类型
     * @param number $tid
     * @param string $content
     * @param unknown $other
     */
    public function notice($from, $to, $type=1, $content='',$other='')
    {
        //通知消息体
        $notice = array(
            'user' 	 => $from,
            'content'=> $content,
            'type' 	 => $type,
            'other'   => $other ? $other :new \stdClass(),
            'time'	 => getMillisecond()
        );
        $result = self::message($this->OP_AS, $to['touid'], $notice);

        if (config('app.debug')) {
            $data = array(
                'type'		=> $type,
                'uid'		=> $from['uid'],
                'toUid'		=> $to['toid'],
                'content'	=> $notice['content'],
                'createtime'=> NOW_TIME,
                'status'	=> $result ? 1 : 0
            );
            Log::debug("notice = " . var_export($data, true));
            Log::debug("notice-result = " . $result);
        }

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

}
