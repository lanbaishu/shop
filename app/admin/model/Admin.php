<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/1 0001
 * Time: 下午 4:40
 */
namespace app\admin\model;
use think\Model;

class Admin extends Model{

    //将时间戳转化为日期
    public function getLastTimeAttr($v){
        return date('Y-m-d H:m');
    }
}