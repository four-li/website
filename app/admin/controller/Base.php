<?php
namespace app\admin\controller;

use think\Config;
use app\common\controller\Index as CommonController;

class Base extends CommonController
{
    public $list_rows;       // 每页显示行数

    public function _initialize(){
        $this->request = \think\Request::instance();

        // 每页显示行数  通过get方式传输list_rows 改变每页显示行数数量
        if($this->request->get('list_rows')){
            \think\Config::set([
                'paginate' => [
                    'type'      => 'bootstrap',
                    'var_page'  => 'page',
                    'list_rows' => $this->request->get('list_rows'),
                ],
            ]);
        }

        // get方式传输page参数 显示和改变页数
        if($this->request->get('page')) $this->assign('page', $this->request->get('page'));
    }
}