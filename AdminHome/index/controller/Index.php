<?php
namespace app\index\controller;

use app\index\Model\Adminstrator;

class Index extends supcontroller
{
    public function index()
    {
        return $this->fetch('/Admin/login/index');
    }
    /*
     * 保存数据
     */
    public function create(){
        $data = $this->data();
        $Adminstrator =  Adminstrator::SelectAdminUser($data['name']);
        if(empty($Adminstrator)) return 1;
        if($Adminstrator['ban'] != '1') return 2;
        if(admin_md5($data['pas']) != $Adminstrator['password']) return 3;
        return $this->fetch('/Admin/Home/index');
    }

}
