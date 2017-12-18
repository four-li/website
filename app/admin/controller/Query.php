<?php
namespace app\admin\controller;

use app\admin\controller\Base as BaseController;
use think\Config;
use think\Db;
use think\Request;

class Query extends BaseController
{
    // ajax查询 不限权限
    public function _initialize(){
        parent::_initialize();
        Config::set('default_return_type','json');
    }

    public function password(){
        
    }
    
}