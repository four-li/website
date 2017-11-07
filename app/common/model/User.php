<?php
    namespace app\common\Model;

    use traits\model\SoftDelete;
    class User extends \think\Model{
        protected $autoWriteTimestamp = 'datetime';
        protected $createTime = 'created';
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