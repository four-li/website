<?php
namespace app\admin\controller;

use app\admin\controller\Base as BaseController;
use think\Request;

class Dimension extends BaseController
{
    // 试卷1
    public function master(Request $request){
        // 通过模型查数据， 能进行更多操作， 如转换enabled数值为禁用，正常。  能 软删除等等好处
        $master = model('master');
        $where = '';
        if($request->method() == 'POST'){
            $sreach = $request->post('keywords');
            if($sreach) $where = [
                'test_id' => 1,
                'title|mark' => ['like', '%'.$sreach.'%'],
            ];
        }
        $list = $master->where($where)->paginate();
        $this->assign('list', $list);
        return $this->fetch();
    }

    // 试卷2
    public function son(Request $request){
        // 通过模型查数据， 能进行更多操作， 如转换enabled数值为禁用，正常。  能 软删除等等好处
        $son = model('son');
        $where = '';
        if($request->method() == 'POST'){
            $sreach = $request->post('keywords');
            if($sreach) $where = [
                'test_id' => 1,
                'title|mark' => ['like', '%'.$sreach.'%'],
            ];
        }

        $list = $son->where($where)->paginate();
        foreach($list as &$v){
            $v->master = '1';
        }

        $this->assign('list', $list);
        return $this->fetch();
    }
}