<?php

namespace app\admin\controller;

use app\admin\common\Base;
use app\admin\model\User1_register;
use app\admin\model\User2_register;
use app\admin\model\User3_register;
use app\admin\model\User4_register;
use app\admin\model\User5_register;
use think\Db;
use think\Model;
use think\Request;
use app\admin\model\Admin;
use think\Session;

class Login extends Base
{
    //渲染用户界面
    public function index()
    {
        $this ->alreadyLogin();
        return $this ->view ->fetch('login');
    }
    public function register(){
        return $this ->view ->fetch('register');
    }

    //验证用户身份
    public function check(Request $request)
    {
        //设置status的初始值
        $status = 0;

        //获取表单的数据，并保存在变量中
        $data = $request ->param();
        $userName = $data['username'];
        $password = md5($data['password']);
        $selectuser = $data['alluser'];
//
//        //在admin表中查询，以用户名为条件
//   判断登录账号属于哪一个表
        if($selectuser=="user6"){
            $map = ['username'=>$userName];
            $admin = Admin::get(['username'=>$userName]);
        }
        elseif ($selectuser=="user1"){
            $map = ['username'=>$userName];
            $admin = User1_register::get(['username'=>$userName]);
        }
        elseif ($selectuser=="user2"){
            $map = ['username'=>$userName];
            $admin = User2_register::get(['username'=>$userName]);
        }
        elseif ($selectuser=="user3"){
            $map = ['username'=>$userName];
            $admin = User3_register::get($map);
        }
        elseif ($selectuser=="user4"){
            $map = ['username'=>$userName];
            $admin = User4_register::get($map);
        }
        elseif ($selectuser=="user5"){
            $map = ['username'=>$userName];
            $admin = User5_register::get($map);
        }
        else{
            $admin=null;
        }


        //将用户与密码分开验证

        //如果查询到该用户
        if(is_null($admin)){
            //设置返回信息
            $message = "用户名不存在！";
        }elseif ($admin->password != $password){
            //设置密码不正确的返回信息
            $message = "密码不正确！";
        }else{
            //用户名和密码都通过，代表合法用户
            //修改返回信息
            $status = 1;
            $message = "验证通过，请点击确定进入后台!";

            //更新表中登陆次数与最后登录时间
//            $admin ->setInc('login_count');
//            $admin ->save(['last_time'=>time()]);

            //将用户的登录信息保存到session中，供其他登录器中进行登录判断
            Session::set('user_id',$userName);
            Session::set('user_info',$data);
        }
//        $status = 1;
//        $message = "验证通过，请点击确定进入后台!";
        return ['status'=>$status,'message'=>$message,'selectuser'=>$selectuser];

    }
    //退出登录
    public function logout(Request $request)
    {
        //删除当前用户session信息
        Session::delete('user_id');
        Session::delete('user_info');

        //执行成功，返回登录页面
        $this ->success('注销成功，正在返回...','login/index');
    }
    public  function create(Request $request){
        $data = $request->param(true);
        $status = 0;
        $message = "服务器正在休息,暂定注册！";
//        文件上传
        $file = $request->file('prove');
        if(empty($file)){
            $data['prove'] = "";
            $status2 = 111;
        }
        else{
            $map =[
                'ext'=>'jpg,png,gif,jpeg',
                'size'=>3000000
            ];
            $info = $file->validate($map) ->move(ROOT_PATH.'public/uploads/');
            if(is_null($info)){
                $this->error($file->getError());
            }
            $data['prove'] = $info ->getSaveName();
            $status2 = 555;
        }
        //文件上传内容结束
        $username = $data['username'];
        $data['password'] = md5($data['password']);
        $selectuser = $data['alluser'];
        unset($data['alluser']);
        unset($data['liulan']);
        unset($data['provetext']);
        if($selectuser=="user1"){
            User1_register::create($data);
            $status = 1;
        } elseif ($selectuser=="user2"){
            User2_register::create($data);
            $status = 1;
        }elseif ($selectuser=="user3"){
            User3_register::create($data);
            $status = 1;
        }elseif ($selectuser=="user4"){
            User4_register::create($data);
            $status = 1;
        }elseif ($selectuser=="user5"){
            User5_register::create($data);
            $status = 1;
        }
        if($status==1){
            $message = "注册成功";
        }
        return ['message'=>$message,'status'=>$status2];
    }
}
