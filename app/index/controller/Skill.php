<?php
namespace app\index\controller;

use app\index\controller\Base as BaseController;

// 是时候表演真正的技术了
class Skill extends BaseController
{
    // 社交
    public function index()
    {
        return $this->fetch();
    }

    // 联系我们 聊天插件 {:url("skill/contact")}
    public function contact(){
        return $this->fetch();
    }
}