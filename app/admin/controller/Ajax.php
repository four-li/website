<?php
namespace app\admin\controller;

use app\admin\controller\Base as BaseController;
use app\common\Model\User;
use think\Request;

class Ajax extends BaseController
{
    public function del_user(Request $request){
        $user_id = $request->get('id');
        // 软删除
        $res = User::destroy($user_id);

        return success($res);
    }
}