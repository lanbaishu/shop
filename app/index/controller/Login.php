<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/5 0005
 * Time: 下午 1:21
 */

namespace app\index\controller;
use app\index\common\Base;
use app\index\model\User;
use think\Session;
use app\index\model\Category;
use think\cache\driver\Memcache;
class Login extends Base{
    //登录首页
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
        return view('login/login');
    }

    //登录处理
    public function login(){
        //接受post数据
        $request=request();
        $name=$request->post('username');
        $pass=$request->post('password');
        $pass=md5($pass);

        //验证用户名是否存在
        $res=User::where('username','eq',$name)->value('username');
        if(!empty($res)){
            //用户名存在,验证密码是否匹配
            $pwd=User::where('username','eq',$name)->value('password');
            if($pass === $pwd){
                //登陆成功,修改最后登录时间
                User::where('username','eq',$name)->update([
                    'lastlogin'=>time(),
                ]);

                //设置session
                $res=User::where('username','eq',$name)->find();
                $res=$res->toArray();
                session('user_info',$res);

                //将session_id存到表中
                User::where('username','eq',$name)->update([
                    'session_id'=>session_id()
                ]);
                $this->redirect('index/index');
            }else{
                $this->error('密码不正确','login/index',2);
            }
        }else{
            $this->error('用户名不存在','login/index',2);
        }
    }

    //注册页面
    public function register(){
        //查出顶级栏目的子栏目
        $cate=new Category();
        $res=$cate->select();
        $data=[];
        foreach($res as $v){
            $data[]=$v->toArray();
        }
        $cate=$cate->sons($data,$id=0);
        $this->assign('cate',$cate);
        return view('login/register');
    }

    //处理注册页面
    public function managereg(){
        //接受post数据
        $request=request();
        $name=$request->post('username');
        $email=$request->post('email');
        $tel=$request->post('user_tel');
        $pass1=$request->post('password');
        $pass2=$request->post('rm_pass');
        $checkbox=$request->post('checkbox',0);
        $post=$request->post();
        $post['regtime']=time();

        //查看用户名和邮箱和手机号是否已存在
        $user=new User();
        $res=$user->select();
        $data=[];
        foreach($res as $v){
            $data[]=$v->toArray();
        }
        foreach($data as $v){
            if($v['username'] == $name){
                $this->error('用户名已存在','login/register',2);
            }
            if($v['email'] == $email){
                $this->error('邮箱已被使用','login/register',2);
            }
            if($v['user_tel'] == $tel){
                $this->error('手机号已存在','login/register',2);
            }
        }

        //查看密码是否一致
        if($pass1 !== $pass2){
            $this->error('密码不一致','login/register',2);
        }

        //用户必须同意协议
        if($checkbox == 0){
            $this->error('您未同意用户协议','login/register',2);
        }

        //将用户信息添加到表中
        if(User::create($post,true)){
            $this->redirect('login/index');
        }else{
            $this->error('注册失败','login/register',2);
        }
    }

    //用户退出登录
    public function logout(){
        Session::delete('user_info');
        $this->redirect('index/index');
    }
}