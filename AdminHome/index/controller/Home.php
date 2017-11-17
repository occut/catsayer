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
        return $this->fetch('/Admin/Home/index');
    }

}
