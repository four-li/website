<?php
namespace app\admin\controller;

use app\common\controller\Index as CommonController;
use think\Config;
use think\Session;
use think\auth\Auth;

class Base extends CommonController
{

//    /**
//     * 管理员操作记录
//     * @param $log_url 操作URL
//     * @param $log_info 记录信息
//     */
//    function adminLog($log_info =' 角色管理 '){
//        $add['log_time'] = time();
//        $add['admin_id'] = session('admin_id');
//        $add['log_info'] = $log_info;
//        $add['log_ip'] = request()->ip();
//        $add['log_url'] = request()->baseUrl() ;
//        M('admin_log')->add($add);
//    }
    public $list_rows;       // 每页显示行数

    public function _empty(){
        $this->redirect('error/index');
    }

    public function _initialize(){
        $this->request = \think\Request::instance();
        // 每页显示行数  通过get方式传输list_rows 改变每页显示行数数量
        if($this->request->has('list_rows')){
            \think\Config::set([
                'paginate' => [
                    'type'      => 'bootstrap',
                    'var_page'  => 'page',
                    'list_rows' => $this->request->get('list_rows'),
                ],
            ]);
        }

        if(Config::get('dev_status') == 'prod') $this->check_admin_auth();
        $this->init_conf();
    }


    // 权限检查
    private function check_admin_auth(){
        $user_info = Session::get('user_info');
        if(!$user_info) return $this->error('请先登录','login/login');

        $module = request()->module();
        $controller = request()->controller();
        $action = request()->action();

        $ignore_auth = [
            'admin/Index',
            'admin/Ajax',
        ];

        if(in_array($module.'/'.$controller,$ignore_auth)) return 'ignore auth';

        $auth1 = $module.'/'.$controller.'/'.$action;
        $auth2 = $module.'/'.$controller;
        $auth = new Auth();
        if(!$auth->check("$auth1,$auth2,superAdmin", $user_info['id'])){
            return $this->error('denied access!');
        }
    }

    // 初始设置
    private function init_conf(){
        $base_conf = \think\Db::name('apply_conf')->where('type','default')->select();
        foreach($base_conf as $v){
            $conf[$v['conf_key']] = $v;
        }
    }
}