<?php
namespace app\admin\controller;

use app\admin\controller\Base as BaseController;
use think\Request;

class User extends BaseController
{

    // 用户列表
    public function index(Request $request){
        $user = new \app\common\Model\User();
        $where = '';
        if($request->has('keywords')){
            $sreach = $request->param('keywords');
            if($sreach) $where = [
                    'status|account' => ['like', '%'.$sreach.'%'],
                ];
        }

        $list = $user->where($where)->paginate(4,false,[
            'query' => request()->param(),
        ])->each(function($item, $key){
            $item['name'] = 'aindy';
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

    public function answer_detail($id){
        $data = \think\Db::name('answer_flow')->where(['user_id'=>$id])->order('answer desc')->select();
        $topic = db('topic')->select();
        $arr = [1=>[], 2=>[]];
        $test_1 = '';$test_2='';
        foreach($data as $v){
            $arr[$v['test_id']][] = $v;
        }

        $label = [
            '1' => '符合',
            '0' => '不符合',
        ];

        foreach($arr[2] as $k=>$v){
            foreach($topic as $vv){
                if($vv['mark'] == $v['mark_id']){
                    $vv['label'] = $label[$v['answer']];
                    $vv['date']  = $v['created'];
                    $test_2[] = $vv;
                }
            }
        }

        $label = [
            '4' => '完全符合',
            '3' => '比较符合',
            '2' => '一般符合',
            '1' => '比较不符合',
            '0' => '完全不符合',
        ];

        foreach($arr[1] as $k=>$v){
            foreach($topic as $vv){
               if($v['mark_id'] == $vv['mark']){
                   $vv['label'] = $label[$v['answer']];
                   $vv['date']  = $v['created'];
                   $test_1[] = $vv;
               }
            }
        }

        $user = \app\common\Model\User::get($id);
        $this->assign('user', $user->name);
        $this->assign('one', $test_1);
        $this->assign('two', $test_2);
        return $this->fetch();
    }

}