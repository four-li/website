<?php
namespace app\index\controller;

use think\Config;
use app\common\controller\Index as CommonController;

class Base extends CommonController
{
    var $conf;

    public function _initialize(){
        parent::_initialize();
        $this->assign('nav_list', Config::get('nav_list'));                                         # 头部菜单
        $this->assign('action_name', $this->request->controller().'/'.$this->request->action());    # 请求方法名

        $this->check_apply_conf();
    }

    // 空方法
    public function _empty(){
        $this->redirect('error/index');
    }

    // 应用配置检查
    public function check_apply_conf(){
        $_conf = \think\Db::name('apply_conf')->select();
        foreach($_conf as $k=>$v) $conf[$v['conf_key']] = $v;
        $this->app_conf = $conf;

        if($conf['app_status']['conf_item'] == 0) die('网站维护中..');  # 1 正常 0 维护 2 部分开放

        if($conf['app_status']['conf_item'] == 2){
            $req = $this->request->controller().'/'.$this->request->action();
            if($req != "Blog/index" && $req != 'Blog/article_z1' && $req != 'Blog/article_z2' && $req != 'Index/err'){
//                $this->error();
            }
        }
    }
}