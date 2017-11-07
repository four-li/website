<?php

    return [
        // 是否开启路由
        'url_route_on'           => true,

        //默认错误跳转对应的模板文件
        'dispatch_error_tmpl' => 'index/err',
        //默认成功跳转对应的模板文件
        'dispatch_success_tmpl' => 'index/suc',

        // 前端页面 头部菜单
        'nav_list' => [
            '主页' => [
                'url' => 'Index/index',
                'icon' => 'fa fa-home',
            ],

            '博客' => [
                'url' => 'Blog/index',
                'icon' => 'fa fa-file-text-o',
            ],
            'geek' => [
                'url' => 'Geek/index',
                'icon' => 'fa fa-krw',
            ],
            '最新' => [
                'url' => 'Current/index',
                'icon' => 'fa fa-cc-amex',
            ],
            '关于' => [
                'url' => 'About/index',
                'icon' => 'fa fa-cc-amex',
            ],
        ],

        // 视图输出字符串内容替换
        'view_replace_str'       => [
            'css/'       => '/indy_blog_dev_version/public/static/admin/css/',
            'js/'        => '/indy_blog_dev_version/public/static/admin/js/',
            'img/'       => '/indy_blog_dev_version/public/static/admin/img/',
        ],
    ];