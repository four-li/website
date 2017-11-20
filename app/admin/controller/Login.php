<?php
namespace app\admin\controller;

use app\admin\model\Admin;
use app\common\controller\Index as CommonController;
use think\Db;
use think\Request;
use think\Response;
use think\Session;

class Login extends CommonController
{
    public function login(Request $request){
        if($request->method() && $request->has('_account') ){
            $user = new Admin();
            $_info = $user->where([
                'account'  => $request->param('_account'),
                'password' => md6($request->param('_password')),
            ])->find();
            if(!$_info){
                $this->assign('err_msg','<div class="alert alert-warning">账号或密码错误</div>');
            }else{
                $info = $_info->toArray();
                if($info['status'] == '冻结'){
                    $this->assign('err_msg','<div class="alert alert-warning">账号被冻结</div>');
                    return $this->fetch();
                }

                Session::set('user_info',$info);
                # 记录admin登录信息
                $_info->save_login_info($request);
                # 记录后台登录流水表
                Db::name('handler_login_flow')->insert([
                    'login_ip'   => $request->ip(),
                    'login_time' => get_date(),
                    'admin_id'   => $info['id']
                ]);
                return redirect('index/index');
            }
        }
        return $this->fetch();
    }

    public function logout(){
        $admin = new Admin();
        $info = $admin->find(Session::get('user_info')['id']);
        $info->offline = 0;
        $info->save();
        Session::clear('user_info');
        return redirect('login');
    }

    // 信息更变
    public function reset_info(){

    }
}