<?php
namespace app\admin\controller;

use app\admin\controller\Base as BaseController;
use app\admin\model\Article;
use think\Request;

class Blog extends BaseController
{
    // 博客列表
    public function index(Request $request){
        $admin = new Article();
        $where = '';
        if($request->has('keywords')){
            $sreach = $request->param('keywords');
            if($sreach) $where = [
                'status|account' => ['like', '%'.$sreach.'%'],
            ];
        }

        $list = $admin->where($where)->paginate(4,false,[
            'query' => request()->param(),
        ])->each(function($item, $key){
            return $item;
        });

        $this->assign('list', $list);
        return $this->fetch();
    }

    public function add(Request $request){

        return $this->fetch();
    }

    public function del_user(Request $request){
        $user_id = $request->get('id');
        // 软删除
        $res = \app\admin\model\Admin::destroy($user_id);

        return success($res);
    }

    public function test(){
        return 'test';
    }

    public function demo(){
        return 'demo';
    }

    public function api(){
        $list = db('test')->where('id>100')->order('create_time desc')->select();

        $this->assign('list',$list);
        return $this->fetch();
    }

}