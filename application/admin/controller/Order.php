<?php

namespace app\admin\controller;

use app\admin\common\Base;
use app\admin\model\User1_register;
use app\admin\model\User2_register;
use app\admin\model\User4_register;
use app\admin\model\Yundan;
use app\admin\model\Yundan2;
use app\admin\model\Yundan4;
use think\Db;
use think\Request;

class Order extends Base
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
        $result = Db::table('yundan')->select();
        if($result==null){
            $list = [];
        }else{
            $list = Yundan::paginate(5);
        }
        //链接两个表，获得运单的公司名
        for($i=0;$i<count($list);$i++){
            $username = $list[$i]['user'];
            $data = User1_register::get(['username'=>$username]);
            $unit = $data['unitname'];
            $list[$i]['unit']=$unit;
        }
        $this->view->assign('list',$list);
        return $this ->view ->fetch('order_list');
    }
    public function index2()
    {
        //
        $this ->isLogin();//判断是否登录
        $result = Db::table('yundan2')->select();
        if($result==null){
            $list = [];
        }else{
            $list = Yundan2::paginate(5);
        }
        //链接两个表，获得运单的公司名
        for($i=0;$i<count($list);$i++){
            $username = $list[$i]['user'];
            $data = User2_register::get(['username'=>$username]);
            $unit = $data['unitname'];
            $list[$i]['unit']=$unit;
        }
        $this->view->assign('list',$list);
        return $this ->view ->fetch('order_list2');
    }
    public function index4()
    {
        //
        $this ->isLogin();//判断是否登录
        $result = Db::table('yundan4')->select();
        if($result==null){
            $list = [];
        }else{
            $list = Yundan4::paginate(5);
        }
        //链接两个表，获得运单的公司名
        for($i=0;$i<count($list);$i++){
            $username = $list[$i]['user'];
            $data = User4_register::get(['username'=>$username]);
            $unit = $data['unitname'];
            $list[$i]['unit']=$unit;
        }
        $this->view->assign('list',$list);
        return $this ->view ->fetch('order_list4');
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
