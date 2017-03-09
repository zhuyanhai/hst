<?php

namespace App\Services\User\Controllers;

use App\Services\ServiceAbstract;
use App\Services\User\Helpers\FormatUserInfo;
use Illuminate\Support\Facades\DB;

/**
 * 获取单个用户信息
 *
 * 版本号：v1
 *
 * Class GetInfoV1
 * @package App\Services\User\Controllers;
 */
class GetInfoV1 extends ServiceAbstract
{
    /**
     * 校验请求参数
     *
     * true = 校验通过 false=校验不通过
     * @return boolean
     */
    public function paramsValidate()
    {
        return $this->_validate($this->params, [
            'userid'  => 'required',
        ], [
            'userid.required'  => '参数丢失',
        ]);
    }

    /**
     * 服务必须实现的方法，因为调用服务会自动调用本方法
     *
     * @return array
     */
    public function run()
    {
        //计算距离
        $lat    = (isset($this->params['lat']))?trim($this->params['lat']):''; //纬度
        $lng    = (isset($this->params['lng']))?trim($this->params['lng']):'';  //经度
        $userid = $this->params['userid'];

        if ($lat && $lng) {
            $userModelList = DB::select('SELECT u.*,uf.remark,uf.isstar,uf.getmsg,uf.fauth1,uf.fauth2,fu.picture1,fu.picture2,fu.picture3,fu.cover,sp.id as captain,rel.online as onship,ac.cbname,ac.cbflow,ac.flowleft,ac.islimit,ac.flowday,ac.stime,ac.etime,ac.warn80,ac.warn100,ac.isby,ac.bydaylimit,ac.bystime,ac.byetime,(SELECT COUNT(*) FROM `tc_user_friend` where (uid=? and fid=u.uid)) as ufd,(SELECT COUNT(*) FROM `tc_user_friend` where (fid=? and uid=u.uid)) as fud,(SELECT COUNT(*) FROM `tc_blacklist` where (uid=? and fid=u.uid)) as isblack,round(getDistance(?,?,u.lng,u.lat)) as distance FROM tc_user u LEFT JOIN `tc_user_friend` as uf ON uf.uid=? and uf.fid=u.uid LEFT JOIN `tc_friend_user` as fu ON fu.uid=u.uid LEFT JOIN `tc_ship` as sp ON u.uid=sp.captain LEFT JOIN `tc_relation` as rel ON u.uid=rel.uid and rel.online=1 LEFT JOIN `tc_account` as ac ON u.uid=ac.uid WHERE ( u.lat <> \'\' ) AND ( u.lng <> \'\' ) AND ( u.uid <> \'\' ) ORDER BY distance asc LIMIT 1',
                [$userid, $userid, $userid, $lat, $lng, $userid]);
        } else {
            $userModelList = DB::select('SELECT u.*,uf.remark,uf.isstar,uf.getmsg,uf.fauth1,uf.fauth2,fu.picture1,fu.picture2,fu.picture3,fu.cover,sp.id as captain,rel.online as onship,ac.cbname,ac.cbflow,ac.flowleft,ac.islimit,ac.flowday,ac.stime,ac.etime,ac.warn80,ac.warn100,ac.isby,ac.bydaylimit,ac.bystime,ac.byetime,(SELECT COUNT(*) FROM `tc_user_friend` where (uid=? and fid=u.uid)) as ufd,(SELECT COUNT(*) FROM `tc_user_friend` where (fid=? and uid=u.uid)) as fud,(SELECT COUNT(*) FROM `tc_blacklist` where (uid=? and fid=u.uid)) as isblack FROM tc_user u LEFT JOIN `tc_user_friend` as uf ON uf.uid=? and uf.fid=u.uid LEFT JOIN `tc_friend_user` as fu ON fu.uid=u.uid LEFT JOIN `tc_ship` as sp ON u.uid=sp.captain LEFT JOIN `tc_relation` as rel ON u.uid=rel.uid and rel.online=1 LEFT JOIN `tc_account` as ac ON u.uid=ac.uid WHERE ( u.uid = ? ) ORDER BY u.sort asc LIMIT 1',
                [$userid, $userid, $userid, $userid, $userid]);
        }

        $userModelList[0] = objectToArray($userModelList[0]);

        //格式化用户信息的辅助类
        $result = FormatUserInfo::process($userModelList, $userid);

        return $this->response($result[0]);

    }

}