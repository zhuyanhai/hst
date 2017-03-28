<?php
namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

/**
 * 用户认证 | 后台
 */
class GetUserRegisterAuthV1Controller extends ApiController
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
        return $this->_validate($this->_params, [
            'action' => 'required',
        ], [
            'action.required' => '请告知我你的动作',
        ]);
    }


    /**
     * API 接口对应的执行方法
     *
     * 更新用户表 tc_user > is_register_check | personid | gender
     * 更新绑定表 tc_user_register_check > 根据用户id更新status
     *
     * @return \App\Http\Controllers\response
     */
    public function run()
    {
        /**
         * 动作:
         *      list > 列表
         *      adopt > 通过
         *      reject > 驳回
         *
         */
        switch ($this->_params['action']) {
            case 'list':
                $defaultPage = 20;
                //根据页码获取用户
                $users = DB::table('user')
                    ->select('uid', 'phone', 'is_register_check', 'nickname', 'personid', 'person_front_pic', 'person_back_pic', 'createtime')
                    ->where('is_register_check', '=', $this->_params['type'])
                    ->paginate($defaultPage);
                foreach ($users as $key => $user) {
                    $list['data'][$key]['phone'] = $user->phone;//手机号码
                    $list['data'][$key]['personid'] = $user->personid;//身份证号
                    $list['data'][$key]['nickname'] = $user->nickname;//名字 | 是否是真实姓名
                    $list['data'][$key]['is_register_check'] = $user->is_register_check;//是否被审核 012
                    $list['data'][$key]['person_front_pic'] = $user->person_front_pic;//身份证前
                    $list['data'][$key]['person_back_pic'] = $user->person_back_pic;//身份证后
                    $list['data'][$key]['createtime'] = $user->createtime;//创建时间 | 身份证提交时间
                }
                //用户总数 | 总页码
                $list['total'] = $users->total();
                $list['lastPage'] = $users->lastPage();
                //$list['thisPage'] = $this->_params['page'];
                $result = $list;
                break;
            case 'adopt':

                break;
            case 'reject':

                break;
            default;
        }

        return $this->response($result);
    }
}