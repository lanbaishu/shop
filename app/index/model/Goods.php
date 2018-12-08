<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/5 0005
 * Time: 下午 4:10
 */
namespace app\index\model;
use think\Model;
use app\index\model\Category;
class Goods extends Model{
    protected $table='goods';

    //查出指定栏目下的所有商品
    public function cateGoods($id){
        $cate=new Category();
        $res=$cate->select();
        $data=[];
        foreach($res as $v){
            $data[]=$v->toArray();
        }
        $cate=$cate->cateTree($data,$id);
        $arr=[$id];
        foreach($cate as $v){
            $arr[]=$v['cat_id'];
        }
        $str=implode(',',$arr);
        $res=Goods::where('cat_id','in',$str)->where('is_delete','eq',0)->paginate(9);
        return $res;
    }
}