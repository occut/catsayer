<?php
namespace app\index\controller;

class Index extends Common
{
    public function index()
    {
        //记录浏览
        $this->Record();
        return $this->fetch('/Index/index/index');
    }
    public function message(){

    }
}
