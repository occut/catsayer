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
        $this->request =  Request();
        $this->Jurisdiction();
    }
    /*
     * 权限管理
     */
    public function Jurisdiction(){
        //获取权限
        $pri = $this->memcached->get('pri');
        //当前模块、控制器、方法
        $module=mb_strtolower($this->request->module());
        $controller=mb_strtolower($this->request->controller());
        $action=mb_strtolower($this->request->action());
        $now=$module.'/'.$controller.'@'.$action;
        //如果是首页相关的模块是允许查看的
        if(($module=="index") && ($controller=="index")){
            return;
        }
        //权限判断 无权限无法操作
        if($pri!="*" && !in_array($now,$pri)){
            exit($this->permissionDeny());
        }
    }
    /*
     * 500 错误返回
     */
    function permissionDeny(){
        return $this->fetch("common/errord");
    }
    /*
     * 处理数据
     */
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
