<?php
namespace app\index\controller;

use app\index\model\Test;
use think\Controller;
use think\Db;
use think\db\Query;
use think\Loader;
use app\index\model\Question;
class Study extends Controller
{
    // 查
    public function r()
    {
        $test = new Test();

//        $res = $test->get(1)->toArray();

        $res = $test->get(function($query){
            $query->where(['id'=>1])
            ->field('user,password');
        })->toArray();

        $res = Test::where('id','lt',2)
            ->field('user, password email')
            ->find()->toArray();

        $res = Test::all('1,2,3');

        $res = Test::where(['id'=>['in',[1,2,3,4]]])->order('user desc')->limit(3)->select();  // 常用

        $res = Test::where(['id'=>3])->value('password');

        $res = Test::column('email,user');

//        foreach($res as $k=>$v){
//            $res[$k] = $v->toArray();
//        }

//        foreach($res as $v){
//            dump($v->toArray());
//        }

        dump($res);

//        return $this->fetch();
    }

    // 增
    public function c(){
        // =================================== 常用方法 ====================================
        // 用create方法和save 方法。 能过自动添加创建时间等等  而insert方法不能   返回的是对象

        // create方法增加数据会调用model里的方法  自动添加create_time updatetime等  传递第二个参数true 可以过滤数据库不存在的字段
        // 直接调用返回的对象里面的id属性获取新增id
        $res = Test::create([
            'user' => 1,
            'password'=> 2,
            'email' => 3,
            'notExist' => 'halo',
            'nullField' => 'none'
        ], true);

        // 对象的方式新增  不存在的自动过滤 直接调用 对象里的id 获取新增id
        $res = new Test();

//        $res->user = 3;
//        $res->password = 3;
//        $res->email = 3;
//        $res->not = '123';
//        $res->save();

//      如果你调用save方法进行多次数据写入的时候，需要注意，第二次save方法的时候必须使用isUpdate(false)，否则会视为更新数据。
        // 同 create方法 allowField同create方法第二个参数， true为过滤不存在字段 。 传一个数组 为指定插入字段  sava第二个参数是更新的条件
        $res
            ->allowField(['user', 'email'])
            ->save([
            'user' => 'aaa',
            'password'=> 'bb',
            'email' => 'vvv',
            'notExist' => 'halo',
            'nullField' => 'none'
        ]);

        // 新增批量
//        $res->saveAll([
//            [
//                'user' => 'aaa',
//                'password'=> 'bb',
//                'email' => 'vvv',
//            ],
//            [
//                'user' => 'aaa222',
//                'password'=> 'b333b',
//                'email' => 'vv333v',
//            ]
//        ]);

        // 不要用insert
        $res->insert([
            'user' => 'aaa',
            'password'=> 'bb',
            'email' => 'vvv',
        ]);

        dump($res->id);
    }

    // 更新
    public function u(){

        // 不要用update更新数据， update没有模型的事件 返回的影响行数
//        $res = Test::update([
//            'id' => 1,
//            'user' => '###',
//        ])->where(['id'=>1]);
//
//        // 返回影响行数
//        $res = Test::where(['id' => 1])->update(['user'=>'@@@']);

        // 使用save 或者 saveAll 能使用事件  save会自动过滤不存在字段
        $user = Test::get(1);
        $user->user     = 'thinkphp';
        $user->email    = 'thinkphp@qq.com';
        $user->save();

        // 返回影响行
        $res = $user->save([
            'user' =>   '!!!',
            'password' => '###',
        ],['id'=>['in','4,5,6']]);

        // =================================== 常用方法 save  saveAll isUpdate() allowField() ====================================
        // 过滤字段 true 为过滤不存在的字段
        $test = new Test();
        // post数组中只有name和email字段会写入
        $res = $test->allowField(['user','email'])->save($_POST, ['id' => 7]);

        // 批量
        $data = [
            ['id'=>1, 'user'=>'thinkphp', 'email'=>'thinkphp@qq.com'],
            ['id'=>2, 'user'=>'onethink', 'email'=>'onethink@qq.com']
        ];
        $res = $test->saveAll($data);
//        dump($res);

        // 如果你的数据操作比较复杂，可以显式的指定当前调用save方法是新增操作还是更新操作。  true 为更新  false为新增
        $test->isUpdate(true)
            ->save(['id' => 2, 'user' => '@', 'email'=>[
                'a' => 'bdddd',
                'c' => 'ddd'
            ]]);

        $test->where(['id'=>1])->setInc('abled');

    }

    public function d(){
        // =================================== 常用方法 destroy ===================================
        // 开启了软删除    # 如果传递第二个参数true的话 ，会真的删除数据  以后用这个方法删除数据
//        $res = Test::destroy(10,true);

        // 查询被软删除的数据
        $res = Test::onlyTrashed()->select();

        // delete这种方法不能软删除  这样会直接删除数据
        $res = Test::where(['id'=>12])->delete();
        // delete方法也能软删除 如果传递参数ture  也会真的删除数据
        $test = Test::get(15);
        $res = $test->delete();

        dump($res);
    }

    private function sql($sql){
        if(strpos($sql, 'select') === false || strpos($sql, 'SELECT') === false){
            return Db::execute($sql);
        }
        return DB::query($sql);
    }

    // 下面是sql的练习
    public function join(){

    }

}























