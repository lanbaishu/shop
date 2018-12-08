<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/2 0002
 * Time: 下午 5:47
 */
namespace app\admin\model;
use think\Model;

class Category extends Model{
    protected $table='cate';

    //查出指定栏目的子孙栏目
    public function cateTree($data,$id=0,$lev=0){
        static $arr=[];
        foreach($data as $v){
            if($v['parent_id'] == $id){
                $v['lev']=$lev;
                $arr[]=$v;
                $this->cateTree($data,$v['cat_id'],$lev+1);
            }
        }
        return $arr;
    }
}