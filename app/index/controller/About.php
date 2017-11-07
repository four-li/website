<?php
namespace app\index\controller;

use app\index\controller\Base as BaseController;


class About extends BaseController
{
    public function index()
    {
        return $this->fetch();
    }

    public function info(){
        return $this->fetch();
    }

    public function team(){
        return $this->fetch();
    }
}