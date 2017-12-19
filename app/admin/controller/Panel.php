<?php
namespace app\admin\controller;

use app\admin\controller\Base as BaseController;
use app\admin\model\PasswordHandle;
use think\Request;
use think\Session;

class Panel extends BaseController
{
    public function _initialize(){
        parent::_initialize();
        if(Session::get('user_info')['id'] != 100001){
            return $this->error('warning! denied access!!!','login/login');
        }
    }
    
    // 密码管理
    public function password_index(Request $request){

        $model = new PasswordHandle();
        $where = '';
        if($request->has('keywords')){
            $sreach = $request->param('keywords');
            if($sreach) $where = [
                "account|title" => trim($sreach),
            ];
        }

        $list = $model->where($where)->paginate(false,false,[
            'query' => request()->param(),
        ])->each(function($item){
            return $item->ob = '预留';
        });

        $this->assign('list', $list);
        return $this->fetch();
    }

    public function password_add(Request $request){

        return $this->fetch();
    }

    public function detail(){

    }
}