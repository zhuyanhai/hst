<?php

namespace App\Services\Foundation\Helpers;

use Ixudra\Curl\Facades\Curl;

/**
 * 短信发送
 *
 * Class Sms
 * @package App\Services\Foundation\Helpers;
 */
class Sms
{
    const API = 'http://v.juhe.cn/sms/send?key=[key]&tpl_id=[tmpId]&mobile=[phone]&tpl_value=[content]';
    const KEY = '0442091e755c01930b237a4dd11bd720';

    /**
     * 验证码短信模板id  //【海上通】您的验证码是#code#。如非本人操作，请忽略本短信。
     */
    const TPL_1  = 9100;
    /**
     * 验证码短信模板id  //【海上通】您的验证码是#code#。有效期为#hour#小时，请尽快验证。
     */
    const TPL_2 = 9103;

    public static function send($phone, $tmpId, $content)
    {
        $smsApi = self::API;
        $smsApi = str_replace('[key]', self::KEY, $smsApi);
        $smsApi = str_replace('[phone]',$phone, $smsApi);
        $smsApi = str_replace('[tmpId]',$tmpId, $smsApi);
        $smsApi = str_replace('[content]',urlencode($content), $smsApi);

        $return = json_decode(Curl::to($smsApi)->get(), true);

        //todo
        //writeLog($smsApi);
        //writeLog($return);

        return array('status' => $return['error_code'], 'message' => $return['reason']);
    }
}
