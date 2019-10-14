<?php

namespace app\admin\controller;

use app\admin\common\Base;
use app\admin\model\User1_register;
use app\admin\model\User2_register;
use app\admin\model\User3_register;
use app\admin\model\User4_register;
use app\admin\model\User5_register;
use think\Db;
use think\Request;

class Manager extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        $this ->isLogin();//判断是否登录
        $result = Db::table('user1_register')->select();
        if($result =  null){
            $list = [];
        }else{
            $list = User1_register::paginate(5);
        }
        $this ->view ->assign('list',$list);
        return $this ->view ->fetch('member_list');
    }
    public function index2()
    {
        //
        $this ->isLogin();//判断是否登录
        $result = Db::table('user2_register')->select();
        if($result =  null){
            $list = [];
        }else{
            $list = User2_register::paginate(5);
        }
        $this ->view ->assign('list',$list);
        return $this ->view ->fetch('member_list2');
    }
    public function index3()
    {
        //
        $this ->isLogin();//判断是否登录
        $result = Db::table('user3_register')->select();
        if($result =  null){
            $list = [];
        }else{
            $list = User3_register::paginate(5);
        }
        $this ->view ->assign('list',$list);
        return $this ->view ->fetch('member_list3');
    }
    public function index4()
    {
        //
        $this ->isLogin();//判断是否登录
        $result = Db::table('user4_register')->select();
        if($result =  null){
            $list = [];
        }else{
            $list = User4_register::paginate(5);
        }
        $this ->view ->assign('list',$list);
        return $this ->view ->fetch('member_list4');
    }
    public function index5()
    {
        //
        $this ->isLogin();//判断是否登录
        $result = Db::table('user5_register')->select();
        if($result =  null){
            $list = [];
        }else{
            $list = User5_register::paginate(5);
        }
        $this ->view ->assign('list',$list);
        return $this ->view ->fetch('member_list5');
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
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
    public function edit($id)
    {
        //
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
    }
}
