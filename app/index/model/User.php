<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/5 0005
 * Time: 下午 2:20
 */
namespace app\index\model;
use think\Model;

class User extends Model{

    //自动将密码加密
    public function setPasswordAttr($v){
        return md5($v);
    }
}