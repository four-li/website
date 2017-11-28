<?php
namespace app\admin\controller;

use app\admin\controller\Base as BaseController;
use think\Session;

class Index extends BaseController
{
    public function index()
    {
        $this->assign('menu', $this->get_menu('admin'));
        return $this->fetch();
    }

    public function admin(){
	// sdsds
        return $this->fetch();
    }

    public function demo(){
//        echo 'contr';
    }

}
