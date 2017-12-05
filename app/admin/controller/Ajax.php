<?php
namespace app\admin\controller;

use app\admin\controller\Base as BaseController;
use think\Config;
use think\Db;
use think\Request;

class Ajax extends BaseController
{
    // ajax查询 不限权限
    public function _initialize(){
        parent::_initialize();
        Config::set('default_return_type','json');
    }

    public function set_status($model, $id, $status){
        $save_status = $status?'0':'1';
        $ret = Db::name($model)->where(['id'=>$id])->update(['status'=>$save_status]);
        if(!$ret) return ['code'=>500 ];
        return json(['status'=>$save_status,'code'=>0 ]);
    }

    // 富文本添加博客...
    public function blog_add(Request $request){
        $text = $request->post('content');

        return json(['code'=>0,'msg'=>'success']);
    }
}