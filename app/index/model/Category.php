<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/5 0005
 * Time: 下午 3:38
 */
namespace app\index\model;
use think\Model;

class Category extends Model{
    protected $table='cate';

    //查出指定栏目的子栏目
    public function sons($data,$id=0){
        $arr=[];
        foreach($data as $v){
            if($v['parent_id'] == $id){
                $arr[]=$v;
            }
        }
        return $arr;
    }

    //查出指定栏目的子孙树
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

    //查出指定栏目的家谱树
    public function parentTree($data,$id){
        static $arr=[];
        foreach($data as $v){
            if($v['cat_id'] == $id){
                $arr[]=$v;
                $this->parentTree($data,$v['parent_id']);
            }
        }
        return $arr;
    }

}