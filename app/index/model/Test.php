<?php
    namespace app\index\model;

    use traits\model\SoftDelete;
    class Test extends \think\Model{

        protected $autoWriteTimestamp = 'datetime';
        protected $createTime = 'create_time';
        protected $updateTime = 'update_time';

        // 定义只读字段
        protected $readonly = ['user','email'];

//        // auto 自动完成方法 会在数据插入和新增时  都会调用
//        protected $auto = [
//            'operator_id'
//        ];
//        // 操作员编号
//        public function setOperatorId(){
//            return $_SESSION['uid'];
//        }

        // 页面输出 字段替换
        public function getAbledAttr($value)
        {
            $status = [0=>'禁用',1=>'正常',2=>'待审核'];
            return $status[$value];
        }

        // 修改器
        public function setUserAttr($value)
        {
            return 'setUserAttr____'.strtolower($value);
        }

        // 传第二个参数，会自动传入当前的所有数据数组
        public function setEmailAttr($value,$data){
            if(is_array($data['email']) ){
                return json_encode($value);
            }
            return 'Change__@'.$value;
        }

        // 软删除
        use SoftDelete;
        protected $deleteTime = 'delete_time';

    }