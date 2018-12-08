<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/3 0003
 * Time: 下午 6:12
 */
namespace app\admin\controller;
use app\admin\common\Base;
use app\admin\model\System as SysModel;;
class System extends Base{
    public function index(){
        //查出网站配置信息
        $res=SysModel::where('sys_id','eq',1)->find();
        $res=$res->toArray();
        $this->assign('system',$res);
        return view('system/system_info');
    }

    //网站信息修改
    public function update(){
        //接受post数据
        $request=request();
        $name=$request->post('sys_name');
        $url=$request->post('sys_url');
        $keywords=$request->post('key_words');
        $copyright=$request->post('copyright');
        $icp=$request->post('sys_icp');
        $shield=$request->post('shield_words');
        //修改数据
        SysModel::where('sys_id','eq',1)->update([
            'sys_name'=>$name,
            'sys_url'=>$url,
            'key_words'=>$keywords,
            'copyright'=>$copyright,
            'sys_icp'=>$icp,
            'shield_words'=>$shield
        ]);
        $this->redirect('system/index');
    }


}