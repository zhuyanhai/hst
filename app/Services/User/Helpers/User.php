<?php

namespace App\Services\User\Helpers;

use App\Services\User\Models\UserModel;
use Ixudra\Curl\Facades\Curl;

/**
 * 获取用户基本信息
 *
 * Class User
 * @package App\Services\User\Helpers;
 */
class User
{
    /**
     * 获取用户基本信息
     *
     * @param int $userid 用户ID
     * @return UserModel
     */
    public static function getBaseInfoByUserid($userid)
    {
        $userModel = UserModel::where('uid', $userid)->first();

        return $userModel;
    }

    /**
     * 设置用户信息到openVpn
     *
     * 信息包括：
     * userid 用户id
     * token 用户登陆token
     * patterns 用户省流量模式
     * allowExternalUpdates 是否允许其他app使用
     *
     * @param $userid
     * @param $token
     * @param $patterns
     * @param $allowExternalUpdates
     * @param $headers
     */
    public static function setInfoToOpenVpn($userid, $token, $patterns, $allowExternalUpdates, $headers)
    {
        $openVpnServer = config('site.openVpnServer');
        $url = 'http://' . $openVpnServer['ip'] . ':' . $openVpnServer['port'] . '/api/user/syncTrafficPatternsV1';
        $withHeaders = [];
        foreach ($headers as $hKey=>$hVal) {
            if (preg_match('%hst-%i', $hKey)) {
                array_push($withHeaders, $hKey.':'.$hVal[0]);
            }
        }
        $result = Curl::to($url)
            ->withHeaders($withHeaders)
            ->withData(array( 'userid' => $userid, 'token' => $token, 'patterns' => $patterns, 'allowExternalUpdates' => $allowExternalUpdates))
            ->post();
        $result = json_decode($result);
        if (intval($result->state->code) !== 0) {//如果报错
            //todo log系统
        }
    }

    /**
     * 通知路由器取消限速
     */
    public static function pushFlowLimitCancel($userid, $phone)
    {
        //获取用户所在的所有船id(在线)
        $result = callService('ship.getOnlineUserSidV1', ['userid' => $userid]);
        if ($result['code'] != 0) {
            return false;
        }


    }
}