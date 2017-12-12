<?php
namespace app\admin\model;

use traits\model\SoftDelete;

class Admin extends \think\Model{

    protected $autoWriteTimestamp = 'datetime';
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

//    // 页面输出 字段替换
//    public function getStatusAttr($value)
//    {
//        $status = [0=>'冻结',1=>'正常'];
//        return $status[$value];
//    }

    public function getOfflineAttr($value)
    {
        $status = [0=>'<span class="badge">离线</span>',1=>'<span class="badge badge-info">在线</span>'];
        return $status[$value];
    }

    // 软删除
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    public function save_login_info($request){
        $this->last_login_ip   = $request->ip();
        $this->last_login_time = get_date();
        $this->offline = 1;
        $this->setInc('login_count');
        $this->save();
    }

    // oneToOne
    public function adminItem()
    {
        return $this->hasOne('AdminItem','admin_id');
    }
}