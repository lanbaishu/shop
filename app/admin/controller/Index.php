<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/1 0001
 * Time: 下午 3:21
 */
namespace app\admin\controller;
use app\admin\common\Base;
use app\admin\model\Admin;
class Index extends Base{

    public function index(){

        return view('admin/index');
    }
    public function home(){
        //查出所有管理员信息
        $admin=new Admin();
        $res=$admin->select();
        $data=[];
        foreach($res as $v){
            $data[]=$v->toArray();
        }
        $this->assign('admin',$data);
        return view('admin/index_home');
    }
}