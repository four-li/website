<?php
namespace app\admin\controller;

use app\admin\controller\Base as BaseController;

class Index extends BaseController
{
    public function index()
    {
        return $this->fetch();
    }

    public function admin(){

        return $this->fetch();
    }

    public function demo(){
//        echo 'contr';
    }

}