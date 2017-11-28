<?php
namespace app\index\controller;

use app\index\Model\Adminstrator;
use think\cache\driver\Memcached;
use think\Request;
use think\Session;
use think\Validate;

class Home extends supcontroller
{
    public function index()
    {
        if(!$this->memcached->get('pri')){
            $user_id = Session::get('account')['id'];
            $whereUserRoles['user_id'] = $user_id;
            $user = \app\index\model\UserRoles::get($whereUserRoles)->toArray();
            $whereRoleNodes['role_id'] = $user['role_id'];
            $Nodes = \app\index\model\RoleNodes::where($whereRoleNodes)->select()->toArray();
            $array = [];
            foreach ($Nodes as $k=>$v){
                $data['node_id'] = $v['node_id'];
                $privilege = \app\index\model\Node::get($data);
                $array[] = $privilege['auth'];
            }
            $this->memcached->set('pri', $array);
        }
        return $this->fetch('/Admin/Home/index');
    }

}
