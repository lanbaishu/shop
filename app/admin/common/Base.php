<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/30 0030
 * Time: 下午 7:17
 */
namespace app\admin\common;
use think\Controller;
use app\admin\model\Admin;
class Base extends Controller{
    protected function _initialize(){
        if(empty(session('admin_info'))){
            $this->redirect('login/index');
        }
        //用户每次操作，都会修改操作时间
        Admin::where('admin_id','eq',session('admin_info')['admin_id'])->update([
            'work_time'=>time()
        ]);
    }
}