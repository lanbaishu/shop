<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/4 0004
 * Time: 下午 10:02
 */

namespace app\index\common;
use think\Controller;
use app\index\model\User;
use think\Session;
class Base extends Controller{

    protected function _initialize(){
        if(session('user_info') != null){
            $id=User::where('username','eq',session('user_info')['username'])->value('session_id');
            if(session_id() != $id){
                Session::delete('user_info');
                $this->error('检测到账号在其他设备登录,您被强制下线','login/index',2);
            }
        }

    }
}