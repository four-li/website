<?php
    return [
        'asd' => 'asdsadsad',

        // 是否开启路由
        'url_route_on'           => true,

        // AUTH 权限认证
        'auth'  => [
            'auth_on'           => 1, // 权限开关
            'auth_type'         => 1, // 认证方式，1为实时认证；2为登录认证。
            'auth_group'        => 'auth_group', // 用户组数据不带前缀表名
            'auth_group_access' => 'auth_group_access', // 用户-用户组关系不带前缀表名
            'auth_rule'         => 'auth_rule', // 权限规则不带前缀表名
            'auth_user'         => 'admin', // 用户信息不带前缀表名
        ],

        // 视图输出字符串内容替换
//        'view_replace_str'       => [
//            'css/'       => '/sample/tp5/public/static/admin/css/',
//            'js/'       => '/sample/tp5/public/static/admin/js/',
//            'img/'       => '/sample/tp5/public/static/admin/img/',
//        ],

    ];