<?php
namespace app\index\controller;

use think\Controller;
use think\Request;


class supcontroller extends Controller
{
    /**
     * 构造函数初始化配置信息
     */
    function __construct()
    {
        //父类初始化
        parent::__construct();
        //其他配置加载
        $this->memcached = new \think\Cache\Driver\Memcache;

    }

     function data(){

         $request =  Request();
         $data =$request->param();
            if(isset($data['_token']))                      unset($data['_token']);
            if(isset($data['_method']))                     unset($data['_method']);
            if(isset($data['file']) && $data['file'] == '') unset($data['file']);
            array_walk($data, function(&$v, $k) {
                if(!is_array($v) && !is_object($v)) {
                    $v = trim($v);
                }
            });
        $data['updated_at'] = date("Y-m-d H:i:s");
        return $data;
    }
}
