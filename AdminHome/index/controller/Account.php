<?php

namespace app\index\controller;


use think\Session;
use think\Validate;

class Account extends supcontroller
{
    /**
     * 首页html展示
     * @return mixed
     */
    public function index()
    {
        $account = $this->request->get("username");
        $where = [];
        if (!empty($account)) {
            $where['username|ip|updated_at'] = ['like',"%$account%"];
        }
        $data = \app\index\model\Adminstrator::where($where)->paginate(10);
        $this->assign([
            "data" => $data,
        ]);
        return $this->fetch('/Admin/Account/index');

    }


    public function addAdmin(){
        return view("/Admin/Account/addAdmin");
    }

    public function storeAdmin(){
        //接收数据
        $data = $this->data();
        //验证
        $rule = [
            ["username", "require|unique:adminstrator", "请填写用户名|用户名已经存在"],
            ["password", "require", "请填写密码"],
            ["phone", "require|max:11|/^1[3-8]{1}[0-9]{9}$/", "请填写手机号|格式错误|格式错误"],
        ];
        $validate = new Validate($rule);
        if (!$validate->check($data)) {
            return['msg'=>$validate->getError(),'error'=>false];
        }
        $data['created_at'] = date("Y-m-d H:i:s");
        $data['fid'] =Session::get('account')['id'];
        $data['password'] =admin_md5($data['password']);
        //添加数据/;]'
        $result = \app\index\model\Adminstrator::create($data );
        //返回消息
        if (!$result) {
            return['msg'=>"添加失败",'error'=>false];
        }
        return['msg'=>"添加成功",'error'=>false];
    }


    public function editAdmin(){
        $id = input('id');
        $data['user_id'] = $id;
        $privilege = \app\index\model\Adminstrator::get($data);
        $this->assign([
            "data" => $privilege,
        ]);
        return $this->fetch('/Admin/Account/editAdmin');
    }

    public function updateAdmin(){
        //接收数据
        $data = $this->data();
        //验证
        $rule = [
            ["username", "require|unique:adminstrator", "请填写用户名|用户名已经存在"],
            ["password", "require", "请填写密码"],
            ["phone", "require|max:11|/^1[3-8]{1}[0-9]{9}$/", "请填写手机号|格式错误|格式错误"],
        ];
        $validate = new Validate($rule);
        if (!$validate->check($data)) {
            return['msg'=>$validate->getError(),'error'=>false];
        }
        $data['created_at'] = date("Y-m-d H:i:s");
        $data['password'] =admin_md5($data['password']);
        //添加数据
        $privilege = \app\index\model\Adminstrator::update($data);
        //返回消息
        if (!$privilege) {
            return['msg'=>"更新失败",'error'=>false];
        }
        return['msg'=>"更新成功",'error'=>false];
    }

    public function delAdmin(){
        if(request()->isGet()){
            $id = input('id');
            $this->assign([
                "data" => $id,
            ]);
            return $this->fetch('/Admin/dell/dell');
        }else{
            $id = input('id');
            if (!\app\index\model\Adminstrator::destroy($id)) {
                return['msg'=>"删除失败",'error'=>false];
            }
            return['msg'=>"删除成功",'error'=>false];
        }
    }
}