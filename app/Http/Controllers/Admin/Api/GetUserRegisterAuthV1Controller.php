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
            'type'  => 'required',
        ], [
            'type.required'  => '请告诉我搜索类型',
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
         * 接口被调用默认状态 待审核
         * 0 = 审核不通过
         * 1 = 待审核
         * 2 = 审核已通过
         */
        $users = DB::table('user')
            ->select('uid','phone','is_register_check','nickname','personid','person_front_pic','person_back_pic','createtime')
            ->where('is_register_check','=',$this->_params['type'])
            ->get()
        ;
        return $this->response($users);
    }
}