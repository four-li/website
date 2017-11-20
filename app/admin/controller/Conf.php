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
        $menu = [
            '用户' => [
                'title'  => '用户',
                'route'    => 'admin/User',
                'status' => 1,
                'level'  => 0,
                'show'   => 1,
                'son'    => [
                    [
                        'title'  => '用户列表',
                        'route'    => 'admin/User/index',
                        'status' => 1,
                        'level'  => 1,
                        'is_show' => 1,
                        'is_assign' => 0,
                    ],
                    [
                        'title'  => '用户编辑',
                        'route'    => 'admin/User/edit',
                        'status' => 1,
                        'level'  => 1,
                        'show'   => 0,
                        'is_assign' => 0,
                    ],
                    [
                        'title'  => '用户新增',
                        'route'    => 'admin/User/add',
                        'status' => 1,
                        'level'  => 1,
                        'show'   => 0,
                        'is_assign' => 0,
                    ],
                    [
                        'title'  => '用户删除',
                        'route'    => 'admin/User/del',
                        'status' => 1,
                        'level'  => 1,
                        'show'   => 0,
                        'is_assign' => 0,
                    ],
                ],
            ],
        ];

        $res = Db::name('manager_menu')->where([
            'is_show' => 1,
            'status'  => 1,
        ])->select();

        foreach($res as $v){

            if($v['has_view']){
                echo $v['title'];
            }
            foreach($res as $vv){

            }

            echo '<br>';
        }

//        echo '<pre>';
//        dump($res);
        $this->assign('list', $menu);
        return $this->fetch();
    }

    # === 权限 角色 组
    public function auth(){
        return $this->fetch();
    }

    public function role(){
        return $this->fetch();
    }

    public function route(){
        return $this->fetch();
    }
}