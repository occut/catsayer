<?php

namespace app\index\controller;

use app\index\model\Privilege;
use think\Session;
use think\Validate;

class Role extends supcontroller
{
    /**
     * 角色列表页展示
     */
    public function index()
    {
        $account = $this->request->get("username");
        $where = [];
        if (!empty($account)) {
            $where['rolename'] = ['like',"%$account%"];
        }
        $data = \app\index\model\Roles::where($where)->paginate(10);
        $this->assign([
            "data" => $data,
        ]);
        return $this->fetch('/Admin/Role/index');
    }
    /**
     * 角色添加页面
     */
    public function addview()
    {
        $id = input('id');
        $data['role_id'] = $id;
        $privilege = \app\index\model\Roles::get($data);
        $this->assign([
            "data" => $privilege,
        ]);
        return view("/Admin/Role/addView");
    }
    /**
     * 角色添加数据
     */
    function add(){
        //接收数据
        $data = $this->data();
        //验证
        $rule = [
            ["rolename", "require|unique:Roles", "请填写权限名称|权限名称已经存在"],
        ];
        $validate = new Validate($rule);
        if (!$validate->check($data)) {
            return['msg'=>$validate->getError(),'error'=>false];
        }
        $data['created_at'] = date("Y-m-d H:i:s");
        //添加数据role_nodes
        $privilege = \app\index\model\Roles::create($data);
        //返回消息
        if (!$privilege) {
            return['msg'=>"添加失败",'error'=>false];
        }
        return['msg'=>"添加成功",'error'=>false];
    }
    /*
     * 编辑显示页面
     */
    public function saveView(){
        $id = input('id');
        $data['role_id'] = $id;
        $privilege = \app\index\model\Roles::get($data);
        $this->assign([
            "data" => $privilege,
        ]);
        return $this->fetch('/Admin/Role/saveView');
    }
    /*
    * 编辑保存
    */
    public function save(){
        //接收数据
        $data = $this->data();
        //验证
        $rule = [
            ["rolename", "require|unique:Roles", "请填写权限名称|权限名称已经存在"],
        ];
        $validate = new Validate($rule);
        if (!$validate->check($data)) {
            return['msg'=>$validate->getError(),'error'=>false];
        }
//        $data['created_at'] = date("Y-m-d H:i:s");
        //添加数据
        $privilege = \app\index\model\Roles::update($data);
        //返回消息
        if (!$privilege) {
            return['msg'=>"更新失败",'error'=>false];
        }
        return['msg'=>"更新成功",'error'=>false];
    }
    /*
    * 删除
    */
    public function dell(){
        if(request()->isGet()){
            $id = input('id');
            $this->assign([
                "data" => $id,
            ]);
            return $this->fetch('/Admin/Role/dell');
        }else{
            $id = input('id');
            if (!\app\index\model\Roles::destroy($id)) {
                return['msg'=>"删除失败",'error'=>false];
            }
            return['msg'=>"删除成功",'error'=>false];
        }
    }
    /*
     * 编辑权值
     */
    public function weight(){
        if(request()->isGet()) {
            $id = input('id');
            $a['role_id'] = $id;
            $ids = \app\index\model\RoleNodes::where($a)->select()->toArray();
            $arr = [];
            foreach ($ids as $k=>$v){
                $arr[] = $v['node_id'];
            }
            $data = \app\index\model\Node::select()->toArray();
            $array = [];
            foreach ($data as $v=>$k){
                $array[] = [
                    'node_id' => $k['node_id'],
                    'pid' => $k['pid'],
                    'name' => $k['nodename'],
                    'auth' => $k['auth'],
                    'open' => in_array($k['node_id'],$arr) ?true:'',
                    'checked' => in_array($k['node_id'],$arr) ?true:'',
                ];
            }
            $this->assign([
                 "result" => json_encode($array),
                 "nodeIds" => $id,
                 "id" => $id,
            ]);
            return $this->fetch('/Admin/Role/weight');
        }else{
            $id = input('id');
            $nod = input('node_id',0);
            if($nod != 0){
                $a['role_id'] = $id;
                \app\index\model\RoleNodes::destroy($a);
                $nodeIds = filterId($nod);
                array_map(function($node_id) use($id) {
                    $data['role_id']    = $id;
                    $data['node_id']    = $node_id;
                    $data['created_at'] = date("Y-m-d H:i:s");
                    $data['updated_at'] = date("Y-m-d H:i:s");
                    \app\index\model\RoleNodes::create($data);
                },$nodeIds);
            }
            return['msg'=>"修改成功",'error'=>false,'url'=>url('Role/index')];
        }
    }
}