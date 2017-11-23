<?php
namespace app\admin\controller;

use app\admin\controller\Base as BaseController;
use app\admin\model\Admin;
use think\Config;
use think\Request;

class User extends BaseController
{
    // 用户列表
    public function index(Request $request){
        $admin = new Admin();
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
        return $this->fetch('user/index');
    }

    public function add(Request $request){
        $post = $request->post();
        if($post['password_1'] != $post['password_2']) return $this->error('两次密码不一致');

        $user = new \app\common\Model\User();
        $single = $user::get(['account'=>$post['account']]);
        if($single) return $this->error('账号已存在');

        $user->account  = $post['account'];
        $user->password = md6($post['password_1']);
        $user->save();
//      echo  $user->id;
        return $this->success('添加成功','demo','',1);
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