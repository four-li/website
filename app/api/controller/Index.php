<?php
    namespace app\api\controller;

    use app\api\controller\Base as BaseController;
    use think\Cache;
    use think\Request;
    use think\Db;

    class Index extends BaseController{
        public function index(){
            $redis = new \RedisDb();
            $redis->setex('name','10','老陈~ob');
            echo $redis->get('name');
//            $this->check_token();
        }

        public function news($id = 1){
            // 启动 路由
            // ecs.c-cf.cn/public/news/44
            return url('index/news', ['id'=>$id]).'<hr/>';
        }

        public function demo(){
            // 支持参数绑定
            Db::query('select * from user where id=?',[1]);
//            Db::execute('insert into user (name,password) values (?, ?)',['indy','456789']);
            // 也支持命名占位符绑定，例如：
//            Db::query('select * from user where id=:id',['id'=>8]);
//            Db::execute('insert into user (name,password) values (:name, :password)',['name' => 123, 'password' => '13123']);

            $data = ['name'=>'idy', 'password'=>'asd'];
            // insertGetId() 插入一条数据并返回最后id
            Db::name('user')->insertGetId($data);
            // 插入多条数据
            Db::name('user')->insertAll($data);

            // 自增 score 字段
            db('user')->where('id', 1)->setInc('score');

            $res = \think\Db::name('user')->where(['name'=>'indy'])->field('name,id')->select();
            dump($res);
        }

        // api 测试
        public function api(){
            return error(['msg'=>'asdsa']);
        }

        // 获取题目
        public function get_topic(Request $request){
            // get 参数 试卷号
            if(!$request->param('type')) return error('缺失参数');

            $tmp = Db::name('topic')->where(['test_id'=> $request->param('type')])->select();
            if(!$tmp) return error('查无数据');

            switch( $request->param('type') ){
                case '1':
                    $data = $tmp;
                    break;

                case '2':
                    foreach($tmp as $v){
                        $arr[$v['belong_to']][] = $v;
                    }
                    foreach($arr as $v){
                        $data[] = $v;
                    }
                    break;
                default :
                    return error('参数错误');
                    break;
            }

            return success($data);
        }
    }



















