<?php
namespace app\index\controller;

use app\index\controller\Base as BaseController;

class Current extends BaseController
{
    # 最新
    public function index()
    {
        return $this->fetch();
    }
}