<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/1 0001
 * Time: 下午 9:08
 */
namespace app\admin\controller;
use app\admin\common\Base;
use app\admin\model\Category;
class Products extends Base{
    public function index(){
        //查出顶级栏目的子孙栏目
        $cate=new Category();
        $res=$cate->select();
        $data=[];
        foreach($res as $v){
            $data[]=$v->toArray();
        }
        $data=$cate->cateTree($data,$id=0);
        $this->assign('cate',$data);
        return view('products/products_list');
    }

    //商品添加界面渲染
    public function add(){
        return view('products/products_add');
    }
}