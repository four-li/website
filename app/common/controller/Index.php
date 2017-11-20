<?php
    namespace app\common\controller;

    use think\Controller;
    use think\Request;

    class Index extends Controller
    {
        public $request;

        public function __construct(){
            parent::__construct();
            $this->request = \think\Request::instance();
        }

        public function _initialize()
        {
            // 是否为手机访问
//            if (Request::instance()->isMobile()) echo "当前为手机访问";
        }

        public function test(){
            return '这里是common模块';
        }

        public function commonAction(){
            return '这是个公共方法';
        }
    }