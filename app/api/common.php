<?php

    // 获取app加密签名
    function get_app_sign($app_key, $app_screct){
        $entry_str = \think\Config::get('app_sign_entry_string');
        return md5($app_key.$app_screct.$entry_str);
    }

    // 检查参数
    function check_param($arr){
        foreach($arr as $v){
            if(!request()->param($v)){
                return true;
            }
        }

        return false;
    }