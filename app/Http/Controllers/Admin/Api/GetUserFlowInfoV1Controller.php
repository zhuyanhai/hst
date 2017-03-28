<?php
namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

class GetUserFlowInfoV1Controller extends ApiController
{

    /**
     * 定义接口必须登录才可以被访问
     *
     * @var bool true＝必须登录 false＝可以不登陆就访问
     */
    protected $foreLogin = false;

    /**
     * 校验请求参数
     *
     * true = 校验通过 false=校验不通过
     * @return boolean
     */
    protected function paramsValidate()
    {
        return true;
    }


    /**
     * API 接口对应的执行方法
     *
     * @return \App\Http\Controllers\response
     */
    public function run()
    {
        $list = [];
        $serviceParams = $this->_params;
        $serviceParams['_apiHeaders'] = $this->_headers;

        /**
         * 所有用户流量统计 、|  无排序规则 , 暂时使用页面获取,大数据会造成深度查询。在想办法。
         */

        //每页显示多少条
        $defaultPage = 20;

        //根据页码获取用户
        $users = DB::table('user')->paginate($defaultPage);

        //循环用户,根据用户查询cost_log
        foreach ($users as $user) {
            $list['list'][] = $this->findCostLogByUser($user);
        }

        //用户总数 | 总页码
        $list['total'] = $users->total();
        $list['lastPage'] = $users->lastPage();

        return $this->response($list);
    }


    /**
     * 获取用户log
     */
    public function findCostLogByUser($user)
    {
        $dayStart = strtotime(date('Ymd') . '000000');
        $dayEnd = strtotime(date('Ymd') . '235959');
        $monthStart = strtotime(date('Ymd') . '000000 -29 days');
        $monthEnd = strtotime(date('Ymd') . '235959');

        //day
        $userDayCost = DB::table('cost_log')
            ->select(DB::raw('SUM(cost) as cost'))
            ->where('uid', $user->uid)
            ->whereBetween('time', [$dayStart, $dayEnd])
            ->pluck('cost');

        //week
        $userWeekCost = DB::table('cost_log')
            ->select(DB::raw('SUM(cost) as cost'))
            ->where('uid', $user->uid)
            ->whereBetween('time', [$dayStart - 6 * 24 * 60 * 60, $dayEnd])
            ->pluck('cost');

        //month
        $userMonthCost = DB::table('cost_log')
            ->select(DB::raw('SUM(cost) as cost'))
            ->where('uid', $user->uid)
            ->whereBetween('time', [$monthStart, $monthEnd])
            ->pluck('cost');

        $result = [
            'uid' => $user->uid,
            'phone' => $user->phone,
            'nickname' => $user->nickname,
            'dcost' => $userDayCost[0] ? $userDayCost[0] : 0,
            'wcost' => $userWeekCost[0] ? $userWeekCost[0] : 0,
            'mcost' => $userMonthCost[0] ? $userMonthCost[0] : 0
        ];

        return $result;
    }
}
