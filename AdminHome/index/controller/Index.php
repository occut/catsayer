<?php
namespace app\index\controller;

use app\index\Model\Adminstrator;
use think\cache\driver\Memcached;
use think\Request;
use think\Session;
use think\Validate;

class Index extends supcontroller
{
    public function index()
    {
        return $this->fetch('/Admin/login/index');
    }
    /*
     * 登录
     */
    public function create(){
        //接收数据
        $data = $this->data();
        //验证规则
        $rules = [
             'name'  => 'require|max:16',
             'pas'   => 'require|max:16',
        ];
        //验证信息
        $msg = [
           'name.require' => '名称必须',
            'name.between'     => '名称只能在6-16之间',
            'pas.require'   => '密码必须',
            'age.between'  => '密码只能在1-16之间',
        ];
        //实例化验证类

        $validate = new Validate($rules,$msg);
        //判断数据是否正确
        if(!$validate->check($data)){
            Session::flash('msg',$validate->getError());
            return $this->redirect('index/index');
        };
        //用户名返回用户数据
        $Adminstrator =  Adminstrator::SelectAdminUser($data['name']);
        //判断用户是否存在
        if(empty($Adminstrator)){
            Session::flash('msg','登录失败');
            return $this->redirect('index/index');
        }
        //判断用户用户是否禁用
        if($Adminstrator['ban'] != '1'){
            Session::flash('msg','登录失败');
            return $this->redirect('index/index');
        }
        //判断用户密码是否正确
        if(admin_md5($data['pas']) != $Adminstrator['password']){
            Session::flash('msg','登录失败');
            return $this->redirect('index/index');
        }
        //登录成功写入session
        Session::set('account',[
            'id'=>$Adminstrator['user_id'],
            'name'=>$Adminstrator['username'],
        ]);
        //更新用户数据
        $request = Request::instance();
        $updata['updated_at'] = date('Y-m-d H:i:s');
        $updata['ip'] = $request->ip();
        $adminModel = new Adminstrator();
        $adminModel->save($updata,['user_id' =>$Adminstrator['user_id']]);
        //登录成功跳转
        return $this->fetch('/Admin/Home/index');
    }
}
