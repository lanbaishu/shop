<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/6 0006
 * Time: 下午 3:23
 */
namespace app\index\controller;
use app\index\common\Base;
use app\index\model\Category;
use app\index\model\Goods;
use app\index\model\Checkout as checkModel;
use think\cache\driver\Memcache;
class Checkout extends Base{
    public function index(){
        if(session('user_info') == null){
            $this->error('请先登录','login/index',2);
        }
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

        //查出购物车表中的信息
        $check=new CheckModel();
        $res=CheckModel::where('user_id','eq',session('user_info')['user_id'])->select();
        $data=[];
        foreach($res as $v){
            $data[]=$v->toArray();
        }
        $this->assign('check',$data);

        //查出购物车中商品总数
        $num=CheckModel::where('user_id','eq',session('user_info')['user_id'])->sum('num');
        $this->assign('num',$num);

        //查出商品总价
        $res=CheckModel::where('user_id','eq',session('user_info')['user_id'])->field("num*shop_price")->select();
        $data=[];
        foreach($res as $v){
            $data[]=$v->toArray();
        }
        $arr=[];
        foreach($data as $v){
            $arr[]=$v['num*shop_price'];
        }
        $sum=array_sum($arr);
        $this->assign('sum',$sum);
        return view('checkout/checkout');
    }

    //将商品添加到购物车
    public function add(){
        if(session('user_info') == null){
            $this->error('请先登录','login/index',2);
        }
        //接受post数据
        $request=request();
        $id=$request->post('goods_id');
        $num=$request->post('num');
        $mod=$request->post('model');

        //查出商品详情
        $res=Goods::where('goods_id','eq',$id)->find();
        $res=$res->toArray();

        $thumb_img=$res['thumb_img'];
        $goods_name=$res['goods_name'];
        $shop_price=$res['shop_price'];
        $market_price=$res['market_price'];

        //添加操作
        CheckModel::create([
            'goods_id'=>$id,
            'num'=>$num,
            'model'=>$mod,
            'thumb_img'=>$thumb_img,
            'goods_name'=>$goods_name,
            'shop_price'=>$shop_price,
            'market_price'=>$market_price,
            'user_id'=>session('user_info')['user_id']
        ]);
        $this->redirect('checkout/index');
    }

    //删除购物车商品
    public function delete(){
        //接受get参数
        $request=request();
        $id=$request->get('check_id');
        CheckModel::where('check_id','eq',$id)->delete();
        $this->redirect('checkout/index');
    }
}
