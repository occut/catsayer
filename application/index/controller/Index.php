<?php
namespace app\index\controller;
use think\Controller;
class Index extends Controller
{
    public function index()
    {
        return $this->fetch('/Index/index/index');
    }
    public function message(){
//var_dump(input());
//die;
        return 1;
    }
}
