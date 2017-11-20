<?php
    namespace app\common\model;

    use traits\model\SoftDelete;
    class User extends \think\Model{

        protected $autoWriteTimestamp = 'datetime';
        protected $createTime = 'create_time';
//        protected $updateTime = 'update_time';
        protected $updateTime = false;

        // 页面输出 字段替换
        public function getStatusAttr($value)
        {
            $status = [0=>'禁用',1=>'正常',2=>'待审核'];
            return $status[$value];
        }

        // 软删除
        use SoftDelete;
        protected $deleteTime = 'delete_time';

    }