<?php

namespace app\admin\controller;

use app\admin\common\Base;
use app\admin\model\Yundan;
use app\admin\model\Yundan2;
use app\admin\model\Yundan4_save;
use think\Db;
use think\Request;
use think\Session;

class User2 extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $this ->isLogin();//判断是否登录
        //返回user2的申报界面
        return $this ->view ->fetch('user2_apply');
    }
    public function index_query1()
    {
        $this ->isLogin();//判断是否登录
        $username = Session::get('user_id');//获取当前登录用户
        $result = Db::table('yundan2')->where('user',$username)->select();//获取所有满足该用户的运单
        if($result==null){
            $query_content = [];//该用户没有运单，则设为空
            $list = [];
        }
        else{
            $query_content = Yundan2::getYundan($result);
            //实现分页功能
            $list = Yundan2::where('user',$username)->paginate(5);
        }
//        //模板赋值
        $this ->view ->assign('query_content',$query_content);
        $this ->view ->assign('list',$list);
        //返回user2的查询界面
        return $this ->view ->fetch('user2_query1');
    }
    public function index_query2()
    {
        $this ->isLogin();//判断是否登录
        $result = Db::table('yundan')->select();
        if($result==null){
            $query_content = [];
            $list = [];
        }
        else{
            $query_content = Yundan::getYundan($result);
//            $list = Db::table('yundan')->where('t3',"")
            $list = Db::table('yundan')->paginate(5);
        }
        $this ->view ->assign('query_content',$query_content);
        $this ->view ->assign('list',$list);
        //返回user2的查询界面
        return $this ->view ->fetch('user2_query2');
    }
    public function index_car()
    {
        $this ->isLogin();//判断是否登录
        $username = Session::get('user_id');
//        $trans = "运输单位".$username;
        $where['car_id'] = array('not like','未分配');
//        $list = Yundan4_save::where('t2',$trans)->where($where)->paginate(5);
        $list = Yundan4_save::where($where)->paginate(5);
        $this ->view ->assign('list',$list);
        return $this ->view ->fetch('user2_car');
    }
    public function index_confirm()
    {
        $this ->isLogin();//判断是否登录
        $username = Session::get('user_id');//获取当前登录用户
        $result = Db::table('yundan2')->where('user',$username)->select();//获取所有满足该用户的运单
        if($result==null){
            $query_content = [];//该用户没有运单，则设为空
            $list = [];
        }
        else{
            $query_content = Yundan2::getYundan($result);
            $list = Yundan2::where('user',$username)->paginate(5);
        }
        $this ->view ->assign('query_content',$query_content);
        $this ->view ->assign('list',$list);
        //返回user2的确认界面
        return $this ->view ->fetch('user2_confirm');
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    //创建运单
    public function create(Request $request)
    {
        //设置status的初始值
        $status = 0;
        $message = "运单提交失败！";
        $username = Session::get('user_id');
        $data = $request ->param();
        //随机生成运单号,7位
        $yundan_id = (string)rand(1000000,9999999);
        $data['yundanid'] = $yundan_id;
        $data['user'] = $username;
        $data['status'] = "未确认";
        //向 user1_yundan 表中添加一条记录
        if(Db::table('yundan2')->insert($data)){
            $status=1;
            $message = "用户".$username."的运单已经提交！\n运单编号为：".$yundan_id;
            $data['user'] = '404';
            Db::table('yundan4_save')->insert($data);
        }
        else{
            $status=0;
            $message = "运单提交失败！";
        }
        return ['status'=>$status,'message'=>$message];
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //编辑运单后进行保存
        $status = 1;
        $message = "保存成功！";
        $data = $request ->param();
        $yundanid = $data['yundanid'];
        $yundan_content = Yundan2::get(['yundanid'=>$yundanid])->save($data);
        if(is_null($yundan_content)){
            $message = "保存失败！";
            $status = 0;
        }
        else{
            $status = 1;
            $message = "保存成功！";
        }
        return ['status'=>$status,'message'=>$message];
    }
    public function save2(Request $request)
    {
        //编辑运单后进行保存
        $status = 1;
        $message = "保存成功！";
        $data = $request ->param();
        $yundanid = $data['yundanid'];
        $yundan_content = Yundan::get(['yundanid'=>$yundanid])->save($data);
        if(is_null($yundan_content)){
            $message = "保存失败！";
            $status = 0;
        }
        else{
            $status = 1;
            $message = "保存成功！";
        }
        return ['status'=>$status,'message'=>$message];
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit(Request $request)
    {
        $this ->isLogin();//判断是否登录
        //获取前端页面传来的运单号
        $yundanid = $request ->param('id');//这里是id，要和user2_query.html中level_edit的命名规则一致
        $content = Yundan2::get($yundanid);//获取该运单内容
        $this ->view ->assign('content',$content);
        return $this ->view ->fetch('user2_edit');
    }
    public function edit2(Request $request)
    {
        $this ->isLogin();//判断是否登录
        //获取前端页面传来的运单号
        $yundanid = $request ->param('id');//这里是id，要和user2_query.html中level_edit的命名规则一致
        $content = Yundan::get($yundanid);
        $this ->view ->assign('content',$content);
        return $this ->view ->fetch('user2_edit2');
    }

    public function report(Request $request)
    {
        $this ->isLogin();//判断是否登录
        //渲染报备单的编辑页面
        $yundanid = $request ->param('id');//这里是id，要和user1_query.html中level_edit的命名规则一致
        $this ->view ->assign('yundanid',$yundanid);
        return $this ->view ->fetch('user2_report');
    }
    //报备单保存
    public function report_save(Request $request)
    {
        //编辑运单后进行保存
        $status = 1;
        $message = "保存成功！";
        $data = $request ->param();
        $yundanid = $data['yundanid'];
        if(Db::table('report')->insert($data)){
            $status=1;
            $message = "运单".$yundanid."的报备单已经提交";
        }
        else{
            $status=0;
            $message = "运单提交失败！";
        }
        return ['status'=>$status,'message'=>$message];
    }
    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function confirm($id)
    {
        //
        $data['status']="已确认";
        Yundan2::get(['yundanid'=>$id])->save($data);
    }
    public function delete($id)
    {
        //
        Yundan2::get(['yundanid'=>$id])->delete();
        if(Yundan4_save::get(['yundanid'=>$id])){
            Yundan4_save::get(['yundanid'=>$id])->delete();
        }
    }
    public function delete2($id)
    {
        //
        Yundan::get(['yundanid'=>$id])->delete();
        if(Yundan4_save::get(['yundanid'=>$id])){
            Yundan4_save::get(['yundanid'=>$id])->delete();
        }
    }
}
