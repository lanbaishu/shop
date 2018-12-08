<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/5 0005
 * Time: 下午 8:15
 */
namespace app\index\controller;
use app\index\common\Base;
use app\index\model\Category;
use app\index\model\Goods;
use think\cache\driver\Memcache;
class Products extends Base{
    public function index(){
        //查出顶级栏目的子栏目并保存到缓存
        $mem=new Memcache;
        $cate=new Category();
        if(empty($mem->get('cate'))){
            $res=$cate->select();
            $data=[];
            foreach($res as $v){
                $data[]=$v->toArray();
            }
            $cate=$cate->sons($data,0);
            $mem->set('cate',$cate);
            $this->assign('cate',$cate);
        }else{
            $cate=$mem->get('cate');
            $this->assign('cate',$cate);
        }

        //查出顶级栏目的子孙栏目
        $cate=new Category();
        $res=$cate->select();
        $data=[];
        foreach($res as $v){
            $data[]=$v->toArray();
        }
        $cateTree=$cate->cateTree($data,0);
        $this->assign('cateTree',$cateTree);

        //接收地址栏参数,查出商品的详细信息并保存到缓存
        $request=request();
        $id=$request->get('goods_id');
        if(empty($mem->get('goods'.$id))){
            $res=Goods::where('goods_id','eq',$id)->find();
            $res=$res->toArray();
            $mem->set('goods'.$id,$res);
            $this->assign('goods',$res);
        }else{
            $res=$mem->get('goods'.$id);
            $this->assign('goods',$res);
        }
        return view('products/single');
    }
}