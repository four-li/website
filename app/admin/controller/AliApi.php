<?php
namespace app\admin\controller;

use app\admin\controller\Base as BaseController;
use app\common\model\MusicTypeFlow;
use app\common\model\QqMusic;
use think\Config;
use think\Db;
use think\Request;

class AliApi extends BaseController
{
    // ajax查询 不限权限
    public function _initialize(){
        parent::_initialize();
        Config::set('default_return_type','json');
    }

    public function qq_music(Request $request){}

    // 添加音乐
    public function add_music($request){
        // 排行 榜行榜id 3=欧美 5=内地 6=港台 16=韩国 17=日本 18=民谣 19=摇滚 23=销量 26=热歌
        $path = "/top";
        $type = $request->param('type')?$request->param('type'):"topid";
        $id   = $request->param('id')?$request->param('id'):"id";
        $param = $path . "?" . $type. '=' .$id;
        $res =  json_decode($this->qq_music_curl($param),true);

        if($res['showapi_res_code'] != 0) return $this->api_error("获取歌曲列表失败".$res['showapi_res_error']);
        $qq_music = new QqMusic();

        $exist = $qq_music::column('song_id');
        foreach($res['showapi_res_body']['pagebean']['songlist'] as $k=>$v){
            if(in_array($v['songid'],$exist)) continue;
            $music_list[$k]['song_name'] = $v['songname'];
            $music_list[$k]['song_id'] = $v['songid'];
            $music_list[$k]['singer_id'] = $v['singerid'];
            $music_list[$k]['albumpic_big'] = $v['albumpic_big'];
            $music_list[$k]['albumpic_small'] = $v['albumpic_small'];
            $music_list[$k]['down_url'] = $v['downUrl'];
            $music_list[$k]['url'] = $v['url'];
            $music_list[$k]['singer_name'] = $v['singername'];
        }

        if(@$music_list){
            $qq_music->saveAll($music_list);
            $music_type_flow = new MusicTypeFlow();
            foreach($music_list as &$v) $v['type'] = $id;
            $music_type_flow->allowField(['song_id', 'type'])->saveAll($music_list);
        }

        return success([],'更新成功');
    }

    // 加歌词
    public function add_music_word($request){
        # 调用参数
        // 歌词查询
        $path    = "/song-word";
        $musicid = $request->param('song_id');
        $param = $path . "?musicid=".$musicid;

        $res =  json_decode($this->qq_music_curl($param),true);
        if($res['showapi_res_code'] != 0){
            $this->api_error("获取歌词失败".$res['showapi_res_error']);
            return error('获取Api失败'.$res['showapi_res_error']);
        }

        $info['lyric'] = serialize($res['showapi_res_body']['lyric']);
        $info['lyric_line'] = $res['showapi_res_body']['lyric_txt'];
        $info['song_id'] = $musicid;

        $res = db('music_word')->insert($info);
        if(!$res) return error('获取失败咯..');
        return success('获取成功..');
    }

    // 查询歌曲并导入本地 (SEO)
    public function search_music($request){
//        $querys = "keyword=%E6%B5%B7%E9%98%94%E5%A4%A9%E7%A9%BA&page=page";
        $path = "/search";
        $kwd = $request->param('keyword');
        $param = $path . "?keyword=".$kwd;

        $res =  json_decode($this->qq_music_curl($param),true);
        if($res['showapi_res_code'] != 0) return $this->api_error("获取歌曲列表失败".$res['showapi_res_error']);

        $arr = $res['showapi_res_body']['pagebean'];
        $data = $arr['contentlist'];

        if( $arr['allPages'] > 1 ){
            for($i=2;$i<=$arr['allPages'];$i++){
                $param = $path . "?keyword=".$kwd."&page=".$i;
                $ret   = json_decode($this->qq_music_curl($param),true)['showapi_res_body']['pagebean']['contentlist'];
                foreach($ret as $v){
                    array_push($data,$v);
                }
                if($res['showapi_res_code'] != 0) return $this->api_error("获取歌曲列表失败".$res['showapi_res_error']);
            }
        }

        $qq_music = new QqMusic();
        $exist = $qq_music::column('song_id');

        // 就最多只存15条
        $i = 1;
        foreach($data as $k=>$v){
            if(in_array($v['songid'], $exist) || $v['singerid'] == 0){
                unset($data[$k]);
                continue;
            }
            if($i > 15) continue;
            $music_list[$k]['song_name'] = @$v['songname'];
            $music_list[$k]['song_id'] = @$v['songid'];
            $music_list[$k]['singer_id'] = @$v['singerid'];
            $music_list[$k]['albumpic_big'] = @$v['albumpic_big'];
            $music_list[$k]['albumpic_small'] = @$v['albumpic_small'];
            $music_list[$k]['down_url'] = @$v['downUrl'];
            $music_list[$k]['url'] = @$v['m4a'];
            $music_list[$k]['singer_name'] = @$v['singername'];
            $i++;
        }

        if(@$music_list){
            $qq_music->saveAll($music_list);
            $music_type_flow = new MusicTypeFlow();
            foreach($music_list as &$v) $v['type'] = 0;
            $music_type_flow->allowField(['song_id', 'type'])->saveAll($music_list);
        }

        return success([],'更新成功');
    }

    public function qq_music_curl($param, $method = 'GET'){
        $conf = Config::get('ali_api.qq_music');
        $host = $conf['host'];
        $appcode = $conf['app_code'];
        $headers = array();
        array_push($headers, "Authorization:APPCODE " . $appcode);
        $url = $host . $param;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        if (1 == strpos("$".$host, "https://"))
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        return curl_exec($curl);
    }

    public function test(){
        return 123;
    }
}