<?php

namespace app\admin\controller;

use app\admin\common\Base;
use app\admin\model\User1_yundan;
use app\admin\model\Yundan;
use app\admin\model\Yundan4_save;
use think\Request;
use think\Db;
use think\Session;

class User1 extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $this ->isLogin();//判断是否登录
        //返回user1的申报界面
        return $this ->view ->fetch('user1_apply');
    }
    public function index_query()
    {
//        $this ->isLogin();//判断是否登录
        $username = Session::get('user_id');//获取当前登录用户
        $result = Db::table('yundan')->where('user',$username)->select();//获取所有满足该用户的运单
//        $result = Db::table('user1_yundan')->where('username='+$username)->select();//获取所有满足该用户的运单
        if($result==null){
            $query_content = [];//该用户没有运单，则设为空
            $list = [];
        }
        else{
            $query_content = Yundan::getYundan($result);
            //实现分页功能
            $list = Yundan::where('user',$username)->paginate(5);
        }
        //模板赋值
        $this ->view ->assign('query_content',$query_content);
        $this ->view ->assign('list',$list);
        //返回user1的查询界面
        return $this ->view ->fetch('user1_query');
    }
    public function index_car()
    {
        $this ->isLogin();//判断是否登录
        $username = Session::get('user_id');
        $where['car_id'] = array('not like','未分配');
        $list = Yundan4_save::where($where)->paginate(5);
        $this ->view ->assign('list',$list);
        return $this ->view ->fetch('user1_car');
    }
    public function index_confirm()
    {
        $this ->isLogin();//判断是否登录
        $username = Session::get('user_id');//获取当前登录用户
        $result = Db::table('yundan')->where('user',$username)->select();//获取所有满足该用户的运单
        if($result==null){
            $query_content = [];//该用户没有运单，则设为空
            $list = [];
        }
        else{
            $query_content = Yundan::getYundan($result);
            $list = Yundan::where('user',$username)->paginate(5);

        }
        $this ->view ->assign('query_content',$query_content);
        $this ->view ->assign('list',$list);
        //返回user1的确认界面
        return $this ->view ->fetch('user1_confirm');
    }

    //创建运单
    public function create(Request $request)
    {
        //设置status的初始值
        $status = 0;
        $username = USER_ID;
        $data = $request ->param();
        //随机生成运单号8位
        $yundan_id = (string)rand(10000000,99999999);
        $data['yundanid'] = $yundan_id;
        $data['user'] = $username;
        $data['status'] = "未确认";
        $data['belong'] = "产泥单位".$username;
        //向 user1_yundan 表中添加一条记录
        if(Db::table('yundan')->insert($data)){
            $status=1;
            $message = "用户".$username."的运单已经提交！\n运单编号为：".$yundan_id;
            $aim = $data['t3'];
            if(preg_match("/^地块\d+/",$aim)){
                $data['user'] = '404';
                Db::table('yundan4_save')->insert($data);
            }
        }
        else{
            $status=0;
            $message = "运单提交失败！";
        }
        return ['status'=>$status,'message'=>$message];
    }

    //寻找搜索结果
    public function show_result(Request $request)
    {
        //
        $status = 0;
        $data = $request ->param();
        $username = Session::get('user_id');//获取当前登录用户
        $yundanid = $data['query_content'];
        $result = User1_yundan::get(['yundanid'=>$yundanid]);
        //判断当前用户是否是该运单的创建者
        if(is_null($result)||$username != $result->username){
            $status = 0;
            $message = "不存在该运单！";
            $new_data=[];
        }
        else{
            $status = 1;
            //获取该运单的部分信息
            $query_result = Yundan::get(['yundanid'=>$yundanid]);
            $new_data["c1"]=$query_result->c1;
            $new_data["c2"]=$query_result->c2;
            $new_data["c3"]=$query_result->c3;
            $new_data["status"]=$query_result->status;
            $message = "查找成功！";
        }
        return ['status'=>$status,'message'=>$message,'newdata'=>$new_data];
    }
    public function show_all()
    {
        //显示所有运单
        $status = 1;
        $username = Session::get('user_id');//获取当前登录用户
//        $result = User1_yundan::get(['username'=>$username]);
        $result = Db::table('yundan')->where('user',$username)->select();//获取所有满足该用户的运单
        $message = "ok";
        for($i=0;$i<count($result);$i++){
            $yundanid = $result[$i]['yundanid'];
            $yundan = Yundan::get(['yundanid'=>$yundanid]);
            $array1['yundanid'] = $yundanid;
            $array1['c1'] = $yundan->c1;
            $array1['c2'] = $yundan->c2;
            $array1['c3'] = $yundan->c3;
            $array2[$i] = $array1;
        }

        return ['status'=>$status,'message'=>$message,'newdata'=>$array2];
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
        $yundanid = $request ->param('id');//这里是id，要和user1_query.html中level_edit的命名规则一致
        $content = Yundan::get($yundanid);//获取该运单内容
        $this ->view ->assign('content',$content);
        return $this ->view ->fetch('user1_edit');
    }

    public function report(Request $request)
    {
        $this ->isLogin();//判断是否登录
        //渲染报备单的编辑页面
        $yundanid = $request ->param('id');//这里是id，要和user1_query.html中level_edit的命名规则一致
        $this ->view ->assign('yundanid',$yundanid);
        return $this ->view ->fetch('user1_report');
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
    public function delete($id)
    {
        //
        Yundan::get(['yundanid'=>$id])->delete();
        if(Yundan4_save::get(['yundanid'=>$id])){
            Yundan4_save::get(['yundanid'=>$id])->delete();
        }
    }
    public function confirm($id)
    {
        //
        $data['status']="已确认";
        Yundan::get(['yundanid'=>$id])->save($data);
    }
    public  function uploaded(Request $request){
        $status = 1;
        $message = "上传成功";
        return ['status'=>$status,'message'=>$message];
    }
}
