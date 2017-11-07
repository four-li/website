<?php
namespace app\index\controller;

use app\index\controller\Base as BaseController;


class News extends BaseController
{
    public function index()
    {
        return $this->fetch();
    }
}