<?php

namespace app\admin\controller;

use app\admin\common\Base;
use app\admin\model\Jianli;
use think\Db;
use think\Request;
use think\Session;

class User5 extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $this ->isLogin();//判断是否登录
        //返回user5的填写界面
        return $this ->view ->fetch('user5_write');
    }
    public function index_query()
    {
        $this ->isLogin();//判断是否登录
        $username = Session::get('user_id');//获取当前登录用户
        $result = Db::table('jianli')->where('user',$username)->select();
        if($result==null){
            $list = [];
        }
        else{
            //实现分页功能
            $list = Jianli::where('user',$username)->paginate(5);
        }
        //模板赋值
        $this ->view ->assign('list',$list);
        //返回user5的查询界面
        return $this ->view ->fetch('user5_query');
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //监理界面下，保存表单数据
        if($this ->request->isPost()){
            //1.获取提交的数据，包括上传文件
            $username = Session::get('user_id');//获取当前登录用户
            $data = $this ->request->param(true);
            //2.获取上传的文件对象
            $file = $this ->request->file('report_file');
            //3.判断是否获取到文件对象
            if(empty($file)){
                $this->error($file->getError());
            }
            //4.校验并上传文件
            $map =[
                'ext'=>'doc,txt,xls',
                'size'=>3000000
            ];
            $info = $file->validate($map) ->move(ROOT_PATH.'public/uploads/');
            if($info==false){
                $this->error('提交格式有误,返回重新提交...');
            }
            //5.获取图片的文件名，并向表中新增数据
            $data['report_file'] =$info ->getSaveName();
            unset($data['temp']);
            unset($data['btn']);
            $data['user'] = $username;
            $res = Jianli::create($data);
            if(is_null($res)){
                $this->error('提交失败');
            }
            return $this ->view ->fetch('user5_query');
        }else{
            $this->error('请求类型错误...');
        }
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
    public  function report(Request $request){

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
