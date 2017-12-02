<?php
namespace app\admin\controller;

use app\admin\controller\Base as BaseController;
use think\Config;
use think\Loader;
use think\Request;
use think\Session;
use think\Db;

class Conf extends BaseController
{
    public function _initialize(){
        parent::_initialize();
        // 只允许ADMIN
        if(Session::get('user_info')['level'] !== 1 && Config::get('dev_status') == 'prod'){
            $this->error('无权访问','error/index');
        }
    }

    public function get_conf_info($type=null){
        if($type !== null){
            $user = Loader::model('User');
            /**
             * @var $user \app\common\model\User;
             */
            $_res = $user::select();
            $user->toArray();
            foreach($_res as $k=>$v){
                $res[$k] = $v->toArray();
            }
            return $res;
        }
        return \think\Db::name('apply_conf')->select();
    }

    public function base(){
        dump($this->get_conf_info(1));
        die;
        return $this->fetch();
    }

    public function web(){

        return $this->fetch();
    }

    # === 菜单
    public function admin_menu(){
//        $memu = $this->get_menu('admin');

        $res = Db::name('manager_menu')->where(['status'=>1,'is_show'=>1])->select();

        foreach($res as $k=>$v){
            $menu[$v['level']][] = $v;
        }

    }

    # === 权限 规则 组
    public function auth(){
        // 角色分配至组
        return $this->fetch();
    }

    public function rule(){
        // curd 规则 和 菜单
        $res = Db::name('manager_menu')->select();

        foreach($res as $k=>$v){
            if($v['pid'] == 0){
                $res[$k]['controller'] = ltrim($v['route'],'admin/');
            }else{
                $res[$k]['action'] = @explode('/', $v['route'])['2']?explode('/', $v['route'])['2']:' - ';
            }
        }

        $this->assign('list', $res);

        return $this->fetch();
    }

    //
    public function group(){
        return $this->fetch();
    }
}