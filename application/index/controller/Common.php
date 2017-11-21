<?php
/**
 * Created by PhpStorm.
 * User: kuan
 * Date: 2017/11/17
 * Time: 10:38
 */

namespace app\index\controller;
use think\Controller;
use think\Request;
use think\Db;
use think\Session;

class Common extends Controller
{
    protected $memcached;
    public function __construct()
    {
        parent::__construct();
        $this->memcached = new \think\Cache\Driver\Memcache;

    }
    //储存访问者的信息
    public function Record(){
        //防刷新
        if(!Session::get('value')){
            $ip = Request::instance()->ip();
            $sql = "select * from kai_accessrecord where ip = '$ip'";
            $voList = Db::query($sql);
            $dateline=strtotime(date("Y-m-d "));
            $datelines=strtotime(date("H:i:s "));
            if(empty($voList)){
                $add = "INSERT INTO `kai_accessrecord`( `ip`, `time`, `time1`, `frequency`) VALUES ('$ip','$dateline','$datelines','1')";
                Db::query($add);
                Session::set('value',true);
            }else{
                $value = $voList[0]['frequency']+1;
                $cave = "UPDATE `kai_accessrecord` SET `frequency` = '$value',`time` = '$dateline',`time1`= '$datelines' WHERE ip = '$ip' ";
                Db::query($cave);
                Session::set('value',true);
            }
        }
        return;
    }
}