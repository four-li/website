<?php
    namespace app\api\controller;

    use think\Db;
    use think\Request;

    class User extends \app\common\controller\Index
    {
        // 注册
        public function register(Request $request){
            if(check_param(['account','password'])) return error('参数缺失');
            $account  = $request->param('account');
            $password = md6($request->param('password'));

            if(Db::name('user')->where(['account' => $account])->field('id')->find()){
                return error('用户名重复');
            }

            Db::name('user')->insert(['account'=>$account,'password'=>$password,'created'=>date('Y-m-d H:i:s', time())]);
            $new_id = Db::name('user')->getLastInsID();
            return $new_id;
        }

        // 登录
        public function login(Request $request){
            if(check_param(['account','password'])) return error('参数缺失');
            $account  = $request->param('account');
            $password = md6($request->param('password'));

            $user = Db::name('user')->where(['account'=>$account,'password'=>$password])->find();

            if(!$user) return error('查无数据');
            unset($user['password']);

            $this->save_session($user);

            return success($user);
        }

        // 保存用户session
        private function save_session($user){
            $key = \think\Config::get('app_sign_entry_string').$user['account'];
            $val = json_encode($user);
            \think\Session::set($key, $val);
            return 0;
        }

    }