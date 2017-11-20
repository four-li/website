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
                'img' => '1',
                'url' => 'Index/index',
                'icon' => 'fa fa-home',
            ],

            '博客' => [
                'img' => '2',
                'url' => 'Blog/index',
                'icon' => 'fa fa-file-text-o',
            ],
            'geek' => [
                'img' => '3',
                'url' => 'Geek/index',
                'icon' => 'fa fa-krw',
            ],
            '最新' => [
                'img' => '4',
                'url' => 'Current/index',
                'icon' => 'fa fa-cc-amex',
            ],
            '关于' => [
                'img' => '5',
                'url' => 'About/index',
                'icon' => 'fa fa-cc-amex',
            ],
        ],
    ];