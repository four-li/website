<?php
namespace app\index\controller;

use app\index\controller\Base as BaseController;


class Geek extends BaseController
{
    public function index()
    {
        $this->assign('list', [1,2,3,4,5,6]);
        return $this->fetch();
    }

    // 3D图片旋转切换
    public function geek_z1(){

        return $this->fetch();
    }

    // 粒子旋风
    public function geek_z2(){
        return $this->fetch();
    }

    // 粒子动画
    public function geek_z3(){
        return $this->fetch();
    }
}