<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/**
 * 管理员密码加密函数
 */
function admin_md5($password) {
    $result = substr((md5(md5($password, true))), 1, 20);
    return $result;
}

if (!function_exists('input')) {
    function input($key = null, $value = '')
    {
        if (is_null($key)) {
            return request()->all();
        }
        return request()->get($key, $value);
    }
}