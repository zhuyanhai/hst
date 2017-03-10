<?php

namespace App\Services\User\Helpers;

use App\Services\User\Models\UserModel;

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
}