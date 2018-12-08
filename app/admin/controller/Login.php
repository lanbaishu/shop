<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/1 0001
 * Time: 下午 4:01
 */
namespace app\admin\controller;
use think\Controller;
use app\admin\model\Admin;
use think\cache\driver\Memcache;
use think\Session;
class Login extends Controller{
    public function index(){
        return view('login/login');
    }

    //管理员登录验证
    public function login(){
        //接受管理员信息
        $request=request();
        $name=$request->post('admin_name');
        $old_pass=$request->post('admin_pass');
        $pass=md5($old_pass);
        $rmpass=$request->post('rm_pass',0);

        //验证用户名是否存在
        $res=Admin::where('admin_name','eq',$name)->value('admin_name');
        if(!empty($res)){
            //走到这说明用户名存在,验证密码是否匹配
            $pwd=Admin::where('admin_name','eq',$name)->value('admin_pass');
            if($pass == $pwd){
                //密码匹配,登陆成功,修改登录次数和最后登录时间
                $count=Admin::where('admin_name','eq',$name)->value('count');
                Admin::where('admin_name','eq',$name)->update([
                    'count'=>$count + 1,
                    'last_time'=>time(),
                    'work_time'=>time()
                ]);
                //设置session
                $res=Admin::where('admin_name','eq',$name)->field('admin_id,admin_name,admin_email,admin_tel,count,last_time')->find();
                $res=$res->toArray();
                if($rmpass == 1){
                    $admin_pass=[];
                    $admin_pass['admin_name']=$name;
                    $admin_pass['admin_pass']=$old_pass;
                    session('admin_pass',$admin_pass);
                }else{
                    Session::delete('admin_pass');
                }
                session('admin_info',$res);
                $this->redirect('index/index');
            }else{
               $this->error('密码错误','login/index',3);
            }
        }else{
           $this->error('用户名不存在','login/index',3);
        }
    }

    //退出登录
    public function logout(){
        Session::delete('admin_info');
        $this->redirect('login/index');
    }
}