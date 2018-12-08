<?php



namespace app\index\controller;
use app\index\common\Base;
use app\index\model\Category;
use app\index\model\Goods;
use think\cache\driver\Memcache;
class Index extends Base{
    public function index(){
        //查出顶级栏目的子栏目并保存到缓存
        $mem=new Memcache;
        if(empty($mem->get('cate'))){
            $cate=new Category();
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

        //查出所有上新商品
        $goods=new Goods();
        $map['is_new']=1;
        $map['is_delete']=0;
        $res=Goods::where($map)->limit(0,8)->select();
        $data=[];
        foreach($res as $v){
            $data[]=$v->toArray();
        }
        $this->assign('new_goods',$data);

        //查出所有热销商品
        $map['is_hot']=1;
        $map['is_delete']=0;
        $res=Goods::where($map)->select();
        $data=[];
        foreach($res as $v){
            $data[]=$v->toArray();
        }
        $this->assign('hot_goods',$data);
        return view('index/index');
    }
}