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

if (!function_exists('supinput')) {
    function supinput($key = null, $value = '')
    {
        if (is_null($key)) {
            return request()->all();
        }
        return request()->get($key, $value);
    }
}
/**
 * 取分类的所有子分类 返回 树形结构数组
 * @return array
 */
function child($array, $id, $idname='id',$pidname='pid',$child='child') {
    $tree = array();
    $new_array = array();
    foreach ($array as $key => $value) {
        $new_array[$value[$idname]] =& $array[$key];
    }

    foreach ($array as $key => $value) {
        $pid =  $value[$pidname];
        if ($id == $pid) {
            $tree[$array[$key][$idname]] =& $array[$key];
        }else{
            if (isset($new_array[$pid])) {
                $parent =& $new_array[$pid];
                $parent['child'][$array[$key][$idname]] =& $array[$key];
            }
        }
    }
    return $tree;
}

/**
 * 迭代 树形结构数组
 * @param  child 方法的返回值
 * @param  回调函数
 * @param  每个子节点的key值
 */
 function treeMap($treeArray,$callback,$child='child') {
    if(!is_callable($callback)) return false;
    foreach ($treeArray as $key => $value) {
        call_user_func($callback,$value);
        if(isset($value[$child]) && count($value[$child])) {
            treeMap($value[$child],$callback,$child);
        }
    }
}
 function filterId($id, $callfunction='intval') {
    return array_filter(explode(',', $id), function($var) use(&$callfunction) {
        $var = call_user_func($callfunction, $var);
        return true;
    });
}