<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function test(){
    echo '1';
}

// 密码单向加密
function md6($str){
    return substr(md5(\think\Config::get('app_sign_entry_string').$str),16);
}

// 通过原始分数获取转换分数
function getScore($originScore){
    return 10 * $originScore + 50;
}

// 通过原始分数获取级别
function getLevel($originScore){
    if($originScore <= -1.5 && $originScore > -5){
        $level = '低频';
    }
    elseif($originScore >= -1.5 && $originScore <= 1.5){
        $level = '中频';
    }
    elseif($originScore > 1.5 && $originScore <= 5){
        $level = '高频';
    }
    else{
        echo '参数不合法';
        die();
    }

    return $level;
}

function getLevel2($originScore){
    if($originScore < 35 && $originScore >= 0){
        $level = '低频';
    }
    elseif($originScore >= 35 && $originScore <= 65){
        $level = '中频';
    }
    elseif($originScore > 65 && $originScore <= 100){
        $level = '高频';
    }
    else{
        echo '参数不合法';
        die();
    }

    return $level;
}


// 返回错误数组
function error($out_data = null, $code = 10001){
    $common_arr = [
        'code'  => $code,
        'msg'   => 'Error',
        'data'  => 0
    ];
    if(is_array($out_data)){
        return array_merge($common_arr, $out_data);
    }elseif(is_string($out_data)){
        $common_arr['msg'] = $out_data;
    }

    return $common_arr;
}

// 返回成功数组
function success($out_data = [], $msg='Success'){
    $common_arr = [
        'code'  => 200,
        'msg'   => $msg,
        'data'  => $out_data
    ];

    if(!empty($out_data['data'])){
        return array_merge($common_arr, $out_data);
    }else{
        $common_arr['data'] = $out_data;
    }

    return $common_arr;
}

// 终止 打印
function p($data){
    echo '<pre>';print_r($data);die;
}
function d($data){
    echo '<pre>';var_dump($data);die;
}