<?php
namespace app\index\controller;

use app\index\controller\Base as BaseController;
use think\Config;
use think\Request;

class Index extends BaseController
{
    public function index()
    {
        return $this->fetch();
    }
    public function socket(){
        return $this->fetch();
    }
    public function err(){
        return $this->fetch();
    }

    public function suc(){

    }
}