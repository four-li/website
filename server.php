<?php
define('APP_PATH', __DIR__ . '/app/');
// 定义配置文件目录
define('CONF_PATH', __DIR__ . '/conf/');

//ajax 跨域
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

define('BIND_MODULE','push/Worker');

// 加载框架引导文件
require __DIR__ . '/thinkphp/start.php';