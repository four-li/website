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

        public function get_menu($type = 'index'){
            switch($type){
                case 'admin':
                    $res = \think\Db::name('manager_menu')->where([
                        'is_show' => 1,
                        'status'  => 1,
                    ])->select();

                    foreach($res as $k=>$v){
                        if($v['level'] == 1){
                            $_res[$k] = $v;
                            foreach($res as $kk=>$vv) {
                                if($vv['level'] == 2 ){
                                    if($vv['pid'] == $v['id']){
                                        $_res[$k]['c'][$kk] = $vv;
                                        foreach($res as $vvv){
                                            if($vvv['pid'] == $vv['id']){
                                                $_res[$k]['c'][$kk]['c'][] = $vvv;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    break;
                case 'index':

                    break;
            }


            return $_res;
        }
    }