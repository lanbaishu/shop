<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/1 0001
 * Time: 下午 7:53
 */
namespace app\admin\controller;
use app\admin\common\Base;
use app\admin\model\Admin as AdminModel;
class Admin extends Base{
    public function index(){
        //查出所有管理员信息
        $admin=new AdminModel();
        $res=$admin->select();
        $data=[];
        foreach($res as $v){
            $data[]=$v->toArray();
        }
        $this->assign('admin',$data);
        return view('admin/admin_info');
    }
}