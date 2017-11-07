<?php
namespace app\index\controller;

use app\index\controller\Base as BaseController;

class Blog extends BaseController
{
    public function index()
    {
        $this->assign('list', [1,2,3,4,5,6,7]);
        return $this->fetch();
    }

    public function article(){
        return $this->fetch();
    }

    public function article_z1(){
        return $this->fetch();
    }

    public function article_z2(){
        return $this->fetch();
    }

}