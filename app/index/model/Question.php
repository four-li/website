<?php
    namespace app\index\model;

    use traits\model\SoftDelete;
    class Question extends \think\Model{

        protected $autoWriteTimestamp = 'datetime';
        protected $createTime = 'create_time';
        protected $updateTime = false;

        // 定义只读字段
        protected $readonly = ['status','is_right'];

//        // auto 自动完成方法 会在数据插入和新增时  都会调用
//        protected $auto = [
//            'operator_id'
//        ];
//        // 操作员编号
//        public function setOperatorId(){
//            return $_SESSION['uid'];
//        }

        // 页面输出 字段替换
        public function getStatusAttr($value)
        {
            if($value == 1){
                return mt_rand(1,1000).'_这个状态非常好啊 不要在改了啊';
            }
        }

        // 修改器
        public function setTitleAttr($value)
        {
            return '^$setTitleAttr___'.strtolower($value);
        }

        // 传第二个参数，会自动传入当前的所有数据数组
        public function setEmailAttr($value,$data){
            if(is_array($data['email']) ){
                return json_encode($value);
            }
            return 'Change__@'.$value;
        }

        protected $deleteTime = false;

    }