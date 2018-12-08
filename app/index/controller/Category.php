<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/4 0004
 * Time: 下午 10:49
 */
namespace app\index\controller;
use app\index\common\Base;
use app\index\model\Goods;
use app\index\model\Category as CateModel;
use think\cache\driver\Memcache;
class Category extends Base{
    public function index(){
        //查出顶级栏目的子栏目并保存到缓存
        $mem=new Memcache;
        $cate=new CateModel();
        if(empty($mem->get('cate'))){
            $res=$cate->select();
            $data=[];
            foreach($res as $v){
                $data[]=$v->toArray();
            }
            $cate=$cate->sons($data,$id=0);
            $mem->set('cate',$cate);
            $this->assign('cate',$cate);
        }else{
            $cate=$mem->get('cate');
            $this->assign('cate',$cate);
        }

        //查出指定栏目下的所有商品
        $request=request();
        $id=$request->get('cat_id');
        $goods=new Goods();
        $goods=$goods->cateGoods($id);
        $this->assign('goods',$goods);

        //查出顶级栏目的子孙树
        $cate=new CateModel();
        $res=$cate->select();
        $data=[];
        foreach($res as $v){
            $data[]=$v->toArray();
        }
        $cateTree=$cate->cateTree($data,0);
        $this->assign('cateTree',$cateTree);

        //查出面包屑导航(家谱树)
        $parentTree=$cate->parentTree($data,$id);
        $parentTree=array_reverse($parentTree);
        $this->assign('parentTree',$parentTree);

        //查出3件上新商品
        $res=Goods::where('is_new','eq',1)->where('is_delete','eq',0)->limit(3)->select();
        $data=[];
        foreach($res as $v){
            $data[]=$v->toArray();
        }
        $this->assign('new_goods',$data);


        return view('category/category');
    }
}