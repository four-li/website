<?php
namespace app\admin\controller;

use app\admin\controller\Base as BaseController;
use think\helper\Time;
use think\Request;

class Topic extends BaseController
{
    // 试卷1
    public function index1(Request $request){

        echo // 天数转换成秒数
        Time::daysToSecond(5); // 零点时间戳
    }

    // 试卷2
    public function index2(Request $request){
        // 通过模型查数据， 能进行更多操作， 如转换enabled数值为禁用，正常。  能 软删除等等好处
        $topic = new \app\common\Model\Topic();
        $where = ['test_id' => 2];
        if($request->method() == 'POST'){
            $sreach = $request->post('keywords');
            if($sreach) $where = [
                'test_id' => 2,
                'title|mark' => ['like', '%'.$sreach.'%'],
            ];
        }
        $list = $topic->where($where)->paginate();

        $this->assign('list', $list);
        return $this->fetch();
    }

    public function demo(Request $request){
        $user = new \app\common\Model\User();
        $where = '';
        if($request->method() == 'POST'){
            $sreach = $request->post('keywords');
            if($sreach) $where = [
                'name|account' => ['like', '%'.$sreach.'%'],
            ];
        }
        $list = $user->where($where)->paginate();

        $this->assign('list', $list);
        $this->assign('total', count($list));
        return $this->fetch('user/demo');
    }

}