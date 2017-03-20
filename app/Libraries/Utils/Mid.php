<?php

namespace App\Libraries\Utils;


/**
 * 数字id 与 字符串 的转换
 *
 * Class Mid
 * @package App\Libraries\Utils
 */
class Mid
{
    private static $_str62keys = array (
        "0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q",
        "r","s","t","u","v","w","x","y","z","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q",
        "R","S","T","U","V","W","X","Y","Z"
    );

    private static $_intkeys = array (
        "0","1","2","3","4","5","6","7","8","9",
    );

    public static function id2URL($mid)
    {
        $mid = (intval($mid)+1020000).'';
        $url = '';
        for ($i = strlen($mid) - 7; $i > -7; $i -= 7){ //从最后往前以7字节为一组读取mid
            $offset1 = $i < 0 ? 0 : $i;
            $offset2 = $i + 7;
            $num = self::_int10to62(substr($mid, $offset1, $offset2));
            $url = $num . $url;
        }
        //return 'M'.self::$_intkeys[mt_rand(1, 9)]. self::$_str62keys[mt_rand(10, 59)] .''.$url;
        if(intval($mid[1]) === 0){
            $f1 = intval($mid[1])+1;
        } else {
            $f1 = $mid[1];
        }
        return 'N'.$f1. $mid[2] .''.$url;
    }

    public static function url2ID($url)
    {
        $mid = '';
        $url = substr($url, 3);
        $rstotal = $strtotal = strlen($url);
        $dj = 0;
        $us  = ($strtotal / 4).'';
        $usa = explode('.',$us);
        $total = intval($usa[0]);
        if(isset($usa[1]) && $usa[1] > 0){
            $total++;
        }
        for($i = 0; $i < $total; ++$i){//循环次数
            $strtotal -= 4;
            if($i == $total-1){
                $substr = substr($url, (($strtotal < 0)?0:$strtotal), $rstotal-$dj);
            } else {
                $substr = substr($url, $strtotal, 4);
            }
            $str = self::_str62to10($substr);
            if ($i > 0){ //若不是第一组，则不足7位补0
                $str = str_pad($str, 7, "0", STR_PAD_LEFT);
            }
            $mid = $str . $mid;
            $dj += 4;
        }
        $mid = intval(ltrim($mid, '0')) - 1020000;
        return $mid;
    }

    private static function _str62to10($str62) { //62进制到10进制
        $strarry = str_split($str62);
        $str = 0;
        for ($i = 0; $i < strlen($str62); $i++) {
            $vi = Pow(62, (strlen($str62) - $i -1));
            $str += $vi * array_search($strarry[$i], self::$_str62keys);
        }
        return $str;
    }

    private static function _int10to62($int10){//10进制到62进制
        $s62 = '';
        $r = 0;
        while ($int10 != 0){
            $r = $int10 % 62;
            $s62 = self::$_str62keys[$r] . $s62;
            $int10 = floor($int10 / 62);
        }
        return $s62;
    }
}
