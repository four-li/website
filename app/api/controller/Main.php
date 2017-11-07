<?php
namespace app\api\controller;

use app\api\controller\Base as BaseController;
use app\common\Model\User;
use think\Request;
use think\Db;

class Main extends BaseController{

    public $user_id;

    public function _initialize(){
        parent::_initialize();
        # 调试时暂时关闭
//        $this->check_token();
        $this->user_id = Db::name('user')->where(['account'=>$this->request->param('account')])->find()['id'];
    }

    // 上传测试题答案
    public function up_answer(Request $request){
        $test_id = $request->param('test_id');
        $msk = $answer_flow_data = \think\Db::name('answer_flow')->where(['user_id'=>$this->user_id,'test_id'=>$test_id])->find();
        if($msk) return error('你已经答过了');
        $bulk    = json_decode($request->param('bulk'),true);
        if(!is_array($bulk)) return error('参数bulk错误');
        if(!$test_id) return error('参数test_id缺失');

        $insert['user_id'] = $this->user_id;
        $insert['test_id'] = $test_id;
        $insert['created'] = date('Y-m-d H:i:s', time());

        // 删除旧数据
        $del = [ 'user_id' => $this->user_id, 'test_id'=> $test_id ];
        Db::name('answer_flow')->where($del)->delete();
        switch($test_id){
            case '1':
                // 试卷一  [{“mark”:1,”answer”:0},{“mark”:2,”answer”:3}]
                // 0 1 2 3 4  顺序对应5种答案
                foreach($bulk as $v){
                    $insert['mark_id'] = $v['mark'];
                    $insert['answer']  = $v['answer'];
                    Db::name('answer_flow')->insert($insert);
                }
                break;

            case '2':
                // 试卷二 [{“belong_to”:”1”,”correct”:”E1”,”error”:”E2”},{“belong_to”:”2”,”correct”:”E4”,”error”:”E5”}]
                // 1 符合  0 不符合
                foreach($bulk as $v){
                    $insert['mark_id'] = $v['correct'];
                    $insert['answer'] = 1;
                    Db::name('answer_flow')->insert($insert);

                    $insert['mark_id'] = $v['error'];
                    $insert['answer'] = 0;
                    Db::name('answer_flow')->insert($insert);
                }
                break;

            default:
                return error('参数错误');
            break;
        }

        $data = Db::name('answer_flow')->getLastInsID();
        if($data) $this->analyse($test_id);
        return success($data);
    }

    // 完善信息接口
    public function fill_info(Request $request){
        if(check_param(['account','name','route','job'])) return error('参数缺失');
        $user = new User();
        $msk = $user->allowField(['name','route','job'])->save($request->param(), ['id' => $this->user_id]);
        if(!$msk) return error('修改失败');
        $info = Db::name('user')->find($this->user_id);
        unset($info['password']);

        return success($info);
    }

    // 存表
    public function analyse($test=''){
//        $master = Db::name('master')->select();
//        foreach($master as $k=>$v){
//            $_final[$v['belong_to']][] = $v;
//        }

        $user_id = $this->user_id;
        $answer = db('answer_flow')->where(['user_id'=>$user_id])->select();

        if(!$answer) return error('查无数据');
        foreach($answer as $v){
            if($v['test_id'] == 1) $test1[] = $v;
            if($v['test_id'] == 2) $test2[] = $v;
        }

        $_topic = db('topic')->select();
        foreach($_topic as $v){
            $topic[$v['mark']] = $v;
        }

        if(@$test1){
            foreach($test1 as $k=>$v){
                $arr1[$k] = $topic[$v['mark_id']];
                $arr1[$k]['answer'] = $v['answer'];
            }
            // son 子维度得分
            $son = [];
            foreach($arr1 as $k=>$v){
                $son[$v['son_id']] = @$son[$v['son_id']]?$son[$v['son_id']]:0;
                switch($v['answer']){
                    case '0':
                        $son[$v['son_id']] -= 1;
                        break;
                    case '1':
                        $son[$v['son_id']] -= 1;
                        break;
                    case '2':
                        $son[$v['son_id']] += 0;
                        break;
                    case '3':
                        $son[$v['son_id']] += 1;
                        break;
                    default:
                        $son[$v['son_id']] += 1;
                        break;
                }
            }
        }

        if(@$test2){
            foreach($test2 as $k=>$v){
                $arr2[$k] = $topic[$v['mark_id']];
                $arr2[$k]['answer'] = $v['answer'];
            }

            foreach($arr2 as $k=>$v){
                $son[$v['son_id']] = @$son[$v['son_id']]?$son[$v['son_id']]:0;
                if($v['answer'] == 1){
                    $son[$v['son_id']] += 1;
                }else{
                    $son[$v['son_id']] -= 1;
                }
            }
        }

        // todo 子维度得分详情
        foreach($son as $k=>$v){
            $son_list[$k]['belong_id'] = $k;
            $son_list[$k]['type'] = 1;
            $son_list[$k]['user_id'] = $user_id;
            $son_list[$k]['score'] = getScore($v);
        }

        Db::name('result')->where(['user_id'=>$user_id,'type'=>1])->delete();
        Db::name('result')->insertAll($son_list);

        $son = Db::name('son')->select();
        foreach($son as $v){
            foreach($son_list as $kk=>$vv){
                if($vv['belong_id'] == $v['id']){
                    $son_list[$kk]['master_id'] = $v['master_id'];
                }
            }
        }

        // todo 主维度得分详情
        foreach($son_list as $k=>$v){
            @$_master[$v['master_id']]['score'] += $v['score'];
        }

        foreach ($_master as $k=>$v) {
            $_master[$k]['score'] = round($v['score']/3);
            $master_list[$k]['belong_id'] = $k;
            $master_list[$k]['type'] = 2;
            $master_list[$k]['user_id'] = $user_id;
            $master_list[$k]['score'] = round($v['score']/3);
        }

        Db::name('result')->where(['user_id'=>$user_id,'type'=>2])->delete();
        Db::name('result')->insertAll($master_list);

    }

    //   是否答题
    public function is_answer(){
        $test_1 = $answer_flow_data = \think\Db::name('answer_flow')->where(['user_id'=>$this->user_id,'test_id'=>1])->find();
        $test_2 = $answer_flow_data = \think\Db::name('answer_flow')->where(['user_id'=>$this->user_id,'test_id'=>2])->find();

        $test['test_1'] = $test_1?1:0;
        $test['test_2'] = $test_2?1:0;

        return success($test);
    }

    // 输出结果
    public function get_result(){
        $user_id = $this->user_id;
        $master_list = Db::name('master')->select(); # 主维度
        $son_list = Db::name('son')->select();       # 子 维
        $source_list = Db::name('source')->select(); # 素材
        $_result_list = Db::name('result')->where(['user_id'=>$user_id])->select(); # 结果表
        if(!$_result_list) return error('未答题');
        # 主维度的最高分 最低分
        $min = ['score' => 100,];
        $max = ['score' => 0,];
        foreach($_result_list as $k=>$v){
            if($v['score'] > $max['score'] && $v['type'] == 2 ){
                $max = [
                    'master_id' => $v['belong_id'],
                    'score' => $v['score'],
                ];
            }
            if($v['score'] < $min['score'] && $v['type'] == 2 ){
                $min = [
                    'master_id' => $v['belong_id'],
                    'score' => $v['score'],
                ];
            }
            $result_list[$v['type']][] = $v;
        }
        # 主维度 频次 解释
        foreach($source_list as $source_v){
            if($source_v['belong_to'] == 2 && $source_v['master_id'] == $max['master_id'] && $source_v['level'] == 3){
                $master_desc['max_desc'][] = $source_v['content'];
            }

            if($source_v['belong_to'] == 2 && $source_v['master_id'] == $min['master_id'] && $source_v['level'] == 1){
                $master_desc['min_desc'][] = $source_v['content'];
            }
        }

        foreach($master_list as $k=>$v){
            $_final[$v['belong_to']][] = $v;
        }
        $tmp = [
            '低频' => 1,
            '中频' => 2,
            '高频' => 3,
        ];

        # 3个管理
        foreach($_final as $k=>$v){
            # master
            foreach($v as $kk=>$vv){
                # 主得分
                foreach($result_list[2] as $vvv){
                    if($vvv['belong_id'] == $vv['id']){
                        $_final[$k][$kk]['score'] = $vvv['score'];
                        $_final[$k][$kk]['level'] = getLevel2($vvv['score']);
                        $_final[$k][$kk]['_level'] = $tmp[$_final[$k][$kk]['level']];
                    }
                }

                # son
                foreach($son_list as $son_v){
                    # 子得分
                    foreach($result_list[1] as $result_1_v){
                        if($son_v['id'] == $result_1_v['belong_id']){
                            $son_v['score'] = $result_1_v['score'];
                            $son_v['level'] = getLevel2($result_1_v['score']);
                            $son_v['_level'] = $tmp[$son_v['level']];
                        }
                    }

                    if($vv['id'] == $son_v['master_id']){
                        # 子维度 频次 解释
                        foreach($source_list as $source_v){
                            if($son_v['id'] == $source_v['son_id'] && $son_v['_level'] == $source_v['level'] && $source_v['belong_to'] == 1 ){
                                $son_v['desc'] = $source_v['content'];
                            }
                        }
                        $_final[$k][$kk]['son'][] = $son_v;
                    }

                }
            }
        }

        $final = [
            'info'   => $_final,
            'figure' => $master_desc,
        ];
        return success($final);
    }

}



















