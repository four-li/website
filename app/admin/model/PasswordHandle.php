<?php
namespace app\admin\model;
use traits\model\SoftDelete;

class PasswordHandle extends \think\Model
{
    protected $autoWriteTimestamp = 'datetime';
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

    // 软删除
    use SoftDelete;
    protected $deleteTime = 'delete_time';


}