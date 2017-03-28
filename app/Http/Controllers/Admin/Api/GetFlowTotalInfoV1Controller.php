<?php
namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

/**
 * Created by PhpStorm.
 * User: abner
 * Date: 17/3/27
 * Time: 上午11:43
 */
class GetFlowTotalInfoV1Controller extends ApiController
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
        /**
         * 循环获取一年的数据
         *
         * 这里的逻辑是 ThinkPhp copy 过来的。
         */
        for ($i = 0, $monthStr1 = $monthStr2 = array(); $i < 12; $i++) {
            $monthS = strtotime(date('Y') . '0101000000 + ' . $i . ' months');
            $monthE = strtotime(date('Y') . '0101000000 + ' . ($i + 1) . ' months') - 1;
            $query = DB::table('cost_log')
                ->select(DB::raw('SUM(cost) as cost'))
                ->where('cost', '>', 0)
                ->where('left', '>', 0)
                ->whereBetween('time', [$monthS, $monthE])
                ->pluck('cost');
            $costSum = round($query[0] / 1024 / 1024, 2);
            $monthStr1[$i] = ($i + 1) . '月';
            $monthStr2[$i] = $costSum;
        }
        $monthStr1 = '[' . implode(',"', $monthStr1) . '"]';
        $monthStr2 = '[' . implode(',', $monthStr2) . ']';
        $data = [
            'column' => $monthStr1,
            'value' => $monthStr2
        ];
        return $this->response($data);
    }

}