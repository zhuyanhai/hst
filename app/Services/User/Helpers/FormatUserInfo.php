<?php

namespace App\Services\User\Helpers;


/**
 * 格式化单个用户的信息
 *
 * Class FormatUserInfo
 * @package App\Services\User\Helpers;
 */
class FormatUserInfo
{
    /**
     * 处理格式化用户信息
     *
     * @param array $userModelList
     * @param int $userid 用户ID
     * @return array
     */
    public static function process($userModelList, $userid = 0)
    {
        $_list = array();
        if ($userModelList) {
            foreach ($userModelList as $k=>$v) {
                $tmp = array();
                $tmp['uid'] = $v['uid'];
                $tmp['sort'] = $v['sort'];
                $tmp['phone'] = $v['phone'];
                unset($tmp['password']);
                if ($userid && ($userid == $v['uid'])) {
                    $tmp['password'] = $v['openfire'];
                }
                $tmp['nickname'] = $v['nickname'];
                $tmp['headsmall'] = $v['headsmall'];
                $tmp['headlarge'] = str_replace('/s_', '/', $v['headsmall']);
                $tmp['gender']	= $v['gender'];//0-男 1-女 2-未填写
                $tmp['sign'] = $v['sign'];
                $tmp['province'] = $v['province'];
                $tmp['city'] = $v['city'];
                $num = $v['ufd'] + ($v['fud'] ? 2 : 0);
                $tmp['isfriend'] = in_array($num, array('1','3')) ? 1 : 0;
                $tmp['isblack'] = $v['isblack'];
                $tmp['verify']	= is_null($v['verify']) ? '' : $v['verify'];
                $tmp['isstar']	= is_null($v['isstar']) ? '0' : $v['isstar'];
                $tmp['frozen']	= $v['frozen'];
                $tmp['flow'] = !isset($v['flow']) ? '0M' : round($v['flow']/1024,2).'M';
                $tmp['captain'] = $v['captain'];
                $tmp['role'] = $v['captain'] ? 1 : 2;
                $tmp['personid'] = $v['personid'];
                $tmp['remark']	= !isset($v['remark']) ? '' : $v['remark'];
                $tmp['getmsg']	= !isset($v['getmsg']) ? '0' : $v['getmsg'];
                $tmp['fauth1']	= !isset($v['fauth1']) ? '0' : $v['fauth1'];
                $tmp['fauth2']	= !isset($v['fauth1']) ? '0' : $v['fauth2'];
                $tmp['picture1'] = !isset($v['picture1']) ? '' : $v['picture1'];
                $tmp['picture2'] = !isset($v['picture2']) ? '' : $v['picture2'];
                $tmp['picture3'] = !isset($v['picture3']) ? '' : $v['picture3'];
                $tmp['cover'] = !isset($v['cover']) ? '' : $v['cover'];
                if ($v['remark']) {//备注名
                    $pinyin = app('pinyin');
                    $pinyinResult = $pinyin->addr($v['remark']);
                    $pinyinResult = explode('-', $pinyinResult);
                    $tmp['sort'] = $pinyinResult[0];
                }
                $tmp['lat']             = $v['lat'];
                $tmp['lng']             = $v['lng'];
                $tmp['distance']        = isset($v['distance']) ? $v['distance'] : -1;
                if (!($v['lat'] && $v['lng'])) $tmp['distance'] = '-1';

                $result = callService('shop.hasUserV1', ['userid'=>$v['uid'], 'status'=>1]);
                $isshop = $result['data']['isHas'];
                $tmp['isshop'] = $isshop;
                if ($isshop) {
                    $tmp['shop'] = $isshop;
                }
                $tmp['onship']  = $v['onship']>=1?1:0;
                $tmp['createtime'] = $v['createtime'];

                //---- acct
                $tmp['cbname'] = $v['cbname'];
                $tmp['cbflow'] = $v['cbflow'];
                $tmp['flowleft'] = $v['flowleft'];
                $tmp['islimit'] = $v['islimit'];
                $tmp['flowday'] = $v['flowday'];
                $tmp['stime'] = $v['stime'];
                $tmp['etime'] = $v['etime'];
                $tmp['stimeStr'] = (!empty($v['stime']))?date('y/m/d',$v['stime']):'';
                $tmp['etimeStr'] = (!empty($v['etime']))?date('y/m/d',$v['etime']):'';
                $tmp['warn80'] = $v['warn80'];
                $tmp['warn100'] = $v['warn100'];
                $tmp['isby'] = $v['isby'];
                $tmp['bydaylimit'] = $v['bydaylimit'];
                $tmp['bystime'] = $v['bystime'];
                $tmp['bystimeStr'] = $v['bystime'] ? date('Y-m-d',$v['bystime']) : '';
                $tmp['byetime'] = $v['byetime'];
                $tmp['byetimeStr'] = $v['byetime'] ? date('Y-m-d',$v['byetime']) : '';
                $_list[] = $tmp;
            }
        }
        return $_list;
    }

}