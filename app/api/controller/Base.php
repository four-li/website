<?php
namespace app\api\controller;

use think\Config;
use app\common\controller\Index as CommonController;
use think\Request;

class Base extends CommonController
{
    public function _initialize(){
        $request = \think\Request::instance();
        // 返回格式
        if($request->has('format')){
            \think\Config::set('default_return_type', $request->get('format'));
        }

        // 检查app_key app_screct  # 暂时注释
//        $this->check_api_auth();
    }

    protected function check_token($account = null){
        if($account == null) $account = $this->request->param('account');
        if(!$account) die(json_encode(error('参数缺失')));
        $key = \think\Config::get('app_sign_entry_string').$account;
        $ret = \think\Session::get($key);
        if(!$ret) die(json_encode(error('未登录')));

        return 0;
    }

    // 未完待续..
    public function check_api_auth(){
        $app_key    = $this->request->get('app_key');
        $app_screct = $this->request->get('app_screct');
        $app_sign   = $this->request->get('app_sign');

        if($app_sign != get_app_sign($app_key,$app_screct)){
            die(json_encode(error('签名不通过')));
        }
    }
}