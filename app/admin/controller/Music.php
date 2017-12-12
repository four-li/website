<?php
namespace app\admin\controller;

use app\admin\controller\Base as BaseController;
use app\common\model\MusicTypeFlow;
use app\common\model\QqMusic;
use think\Db;
use think\Request;

class Music extends BaseController
{
    public function index(Request $request){

        $qq_music = new QqMusic();
        $where = '';
        if($request->has('keywords')){
            $sreach = $request->param('keywords');
            if($sreach) $where = [
//                'status|account' => ['like', '%'.$sreach.'%'],
                "song_name|singer_name" => trim($sreach),
            ];
        }

        $list = $qq_music->where($where)->paginate(false,false,[
            'query' => request()->param(),
        ])->each(function($item){
            return $item->tid = 'asdc';
        });

        $this->assign('list', $list);
        return $this->fetch();
    }

    public function add(Request $request){
        $control = new \app\admin\controller\AliApi();

        if($request->has('top') && $request->post('top')){
            $control->add_music($request);
        }else{
            $control->search_music($request);
        }
        return $this->redirect('index');
    }

}