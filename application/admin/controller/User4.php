<?php

namespace app\admin\controller;

use app\admin\common\Base;
use app\admin\model\Yundan;
use app\admin\model\Yundan2;
use app\admin\model\Yundan4;
use app\admin\model\Yundan4_save;
use think\Db;
use think\Request;
use think\Session;

class User4 extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $this ->isLogin();//判断是否登录
        //返回user4的登记界面
        return $this ->view ->fetch('user4_dengji1');
    }
    public function index_dengji2()
    {
        $this ->isLogin();//判断是否登录
        $username = Session::get('user_id');//获取当前登录用户
        $result = Db::table('yundan4')->where('user',$username)->select();//获取所有满足该用户的运单
        if($result==null){
            $query_content = [];//该用户没有运单，则设为空
            $list = [];
        }
        else{
            $query_content = Yundan4::getYundan($result);
            //实现分页功能
            $list = Yundan4::where('user',$username)->paginate(5);
        }
//        //模板赋值
        $this ->view ->assign('query_content',$query_content);
        $this ->view ->assign('list',$list);
        //返回user4的登记界面
        return $this ->view ->fetch('user4_dengji2');
    }
    public function index_query()
    {
        $this ->isLogin();//判断是否登录
        $username = Session::get('user_id');//获取当前登录用户
        $result = Db::table('yundan4_save')->where('user',$username)->select();//获取所有满足该用户的运单
        if($result==null){
            $list = [];
        }
        else{
            //实现分页功能
            $list = Db::table('yundan4_save')->where('user',$username)->paginate(5);
        }
        $this ->view ->assign('list',$list);
        //返回user4的查询界面
        return $this ->view ->fetch('user4_query');
    }
    public function index_shenhe()
    {
        $this ->isLogin();//判断是否登录
        $username = Session::get('user_id');
        $result = Db::table('yundan4_save')->where('user',$username)->select();//获取所有满足该用户的运单
        if($result==null){
            $list = [];
        }
        else{
            //实现分页功能
            $list = Db::table('yundan4_save')->where('user',$username)->paginate(5);
        }
        $this ->view ->assign('list',$list);
        //返回user4的查询界面
        return $this ->view ->fetch('user4_shenhe');
    }
    public function index_confirm()
    {
        $this ->isLogin();//判断是否登录
        $username = Session::get('user_id');//获取当前登录用户
        $result = Db::table('yundan4_save')->where('user',$username)->select();//获取所有满足该用户的运单
        if($result==null){
            $list = [];
        }
        else{
            //实现分页功能
            $list = Db::table('yundan4_save')->where('user',$username)->paginate(5);
        }
//        //模板赋值
        $this ->view ->assign('list',$list);
        //返回user4的确认界面
        return $this ->view ->fetch('user4_confirm');
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create(Request $request)
    {
        //设置status的初始值
        $status = 0;
        $message = "运单提交失败！";
        $username = Session::get('user_id');
        $data = $request ->param();
        //随机生成运单号,9位
        $yundan_id = (string)rand(100,999);
        $data['yundanid'] = $yundan_id;
        $data['user'] = $username;
        $data['status'] = "未确认";
        $data['kilo'] = rand(20,35);
        //向 user1_yundan 表中添加一条记录
        if(Db::table('yundan4')->insert($data)){
            $status=1;
            $message = "用户".$username."的内容已经提交！\n地块编号为：".$yundan_id;
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
        $yundan_content = Yundan4::get(['yundanid'=>$yundanid])->save($data);
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
        $yundanid = $request ->param('id');//这里是id，要和user4_dengji2.html中level_edit的命名规则一致
        $content = Yundan4::get(['yundanid'=>$yundanid]);//获取该运单内容
        $this ->view ->assign('content',$content);
        return $this ->view ->fetch('user4_edit');
    }
    //渲染审核界面
    public function examine(Request $request){
        $this ->isLogin();//判断是否登录
        $yundanid = $request ->param('id');//这里是id，要和user1_query.html中level_edit的命名规则一致
        $content = Yundan4_save::get(['yundanid'=>$yundanid]);
        $username = Session::get('user_id');//获取当前登录用户
        $result = Db::table('yundan4')->where('user',$username)->select();
        if(!$result){
            $list = [];
        }else{
            $list = Db::table('yundan4')->where('user',$username)->paginate(5);
        }
        $this ->view ->assign('list',$list);
        $this ->view ->assign('content',$content);
        return $this ->view ->fetch('user4_examine');
    }
    public function examine2(Request $request){
        $this ->isLogin();//判断是否登录
        return $this ->view ->fetch('user4_examine2');
    }
    //渲染报错界面
    public  function report(Request $request){
        $this ->isLogin();//判断是否登录
        //渲染报备单的编辑页面
        $yundanid = $request ->param('id');//这里是id，要和user1_query.html中level_edit的命名规则一致
        $this ->view ->assign('yundanid',$yundanid);
        return $this ->view ->fetch('user4_report');
    }
    //报备单保存(暂时为假保存，未写入数据库)
    public function report_save(Request $request)
    {
        //编辑运单后进行保存
        $status = 1;
        $message = "保存成功！";
        $data = $request ->param();
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
    public function examine_confirm($id){
//        $save_data = Db::table('yundan4_save')->where('yundanid',$id);
//        $save_data['user'] = '303';
//        Db::table('yundan3')->insert($save_data);
        $data['check'] = 1;
        Yundan4_save::get(['yundanid'=>$id])->save($data);
    }
    public function confirm($id)
    {
        //
        $data['status']="已确认";
        Yundan4_save::get(['yundanid'=>$id])->save($data);
    }
    public function delete($id)
    {
        //
        Yundan4::get(['yundanid'=>$id])->delete();
    }
}
