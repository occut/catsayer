<?php
namespace app\index\model;

use think\Model;

class Adminstrator extends Model
{
    /*
     * 用户名返回用户数据
     */
    public static function SelectAdminUser($username){
        $admin = new Adminstrator();
        $a['username'] = $username;
        $value = $admin->where($a)->find();
        return $value;
    }
}