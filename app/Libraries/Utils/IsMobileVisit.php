<?php

namespace App\Libraries\Utils;


/**
 * 判断是否手机端访问
 *
 * Class IsMobileVisit
 * @package App\Libraries\Utils
 */
class IsMobileVisit
{
    /**
     * 检查访问请求头信息，判断是否是移动端访问页面
     *
     * @return boolean true = 来至移动端访问 | false = 来至PC端访问
     */
    public static function has()
    {
        if(self::_getMobileType() != 'pc'){
            return true;
        }

        return false;
    }

    /**
     * 获取移动终端类型
     *
     * @return string
     */
    private static function _getMobileType()
    {
        $matchAry = array();
        if($_SERVER && isset($_SERVER['HTTP_USER_AGENT'])){
            preg_match('/(alcatel|amoi|android|avantgo|blackberry|benq|cell|cricket|docomo|elaine|htc|iemobile|iphone|ipad|ipaq|ipod|j2me|java|midp|mini|mmp|mobi|motorola|nec-|nokia|palm|panasonic|philips|phone|sagem|sharp|sie-|smartphone|sony|symbian|t-mobile|telus|up\.browser|up\.link|vodafone|wap|webos|wireless|xda|xoom|zte|ucweb|windows ce|windows mobile|rv:1\.2\.3\.4)/i', $_SERVER['HTTP_USER_AGENT'], $matchAry);
        }
        if(empty($matchAry)){
            return 'pc';
        }
        return $matchAry[0];
    }
}
