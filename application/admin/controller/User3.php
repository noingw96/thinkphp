<?php

namespace app\admin\controller;

use app\admin\common\Base;
use app\admin\model\Dengji;
use app\admin\model\Yundan;
use app\admin\model\Yundan2;
use app\admin\model\Yundan4_save;
use think\Db;
use think\Request;
use think\Session;

class User3 extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $this ->isLogin();//判断是否登录
        //返回user3的申报界面
        return $this ->view ->fetch('user3_apply');
    }
    public function index_query()
    {
        $this ->isLogin();//判断是否登录
        $username = Session::get('user_id');//获取当前登录用户

        $trans = "运输单位".$username;
        $list = Yundan4_save::where('t2',$trans)->where('check',1)->paginate(5);
        $this ->view ->assign('list',$list);
        //返回user3的查询界面
        return $this ->view ->fetch('user3_query');
    }
    public function index_car()
    {
        $this ->isLogin();//判断是否登录
        $username = Session::get('user_id');
        $trans = "运输单位".$username;
        $where['car_id'] = array('not like','未分配');
        $list = Yundan4_save::where('t2',$trans)->where($where)->paginate(5);
        $this ->view ->assign('list',$list);
        return $this ->view ->fetch('user3_car');
    }
    public function index_confirm()
    {
        $this ->isLogin();//判断是否登录
        $username = Session::get('user_id');//获取当前登录用户
        $trans = "运输单位".$username;
        $list = Yundan4_save::where('t2',$trans)->where('check',1)->paginate(5);
//        //模板赋值
        $this ->view ->assign('list',$list);
        return $this ->view ->fetch('user3_confirm');
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //登记界面下，保存表单数据
        if($this ->request->isPost()){
            //1.获取提交的数据，包括上传文件
            $data = $this ->request->param(true);
            //2.获取上传的文件对象
            $file = $this ->request->file('image');
            $file2 = $this ->request->file('image2');
            //3.判断是否获取到文件对象
            if(empty($file)||empty($file2)){
                $this->error($file->getError());
            }
            //4.校验并上传文件
            $map =[
                'ext'=>'jpg,png,gif,jpeg',
                'size'=>3000000
            ];
            $info = $file->validate($map) ->move(ROOT_PATH.'public/uploads/');
            $info2 = $file2->validate($map) ->move(ROOT_PATH.'public/uploads/');
            if(is_null($info)||is_null($info2)){
                $this->error($file->getError());
            }
            //5.获取图片的文件名，并向表中新增数据
            $data['image'] = $info ->getSaveName();
            $data['image2'] = $info2 ->getSaveName();
            $res = Dengji::create($data);
            if(is_null($res)){
                $this->error('提交失败');
            }
            $this->success('提交成功');
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
    public  function report(Request $request){
        $this ->isLogin();//判断是否登录
        //渲染报备单的编辑页面
        $yundanid = $request ->param('id');//这里是id，要和user1_query.html中level_edit的命名规则一致
        $this ->view ->assign('yundanid',$yundanid);
        return $this ->view ->fetch('user3_report');
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
    public function save($id,$car_id)
    {
        //保存运单的车辆信息
        $data['car_id'] =$car_id;
        Yundan4_save::get(['yundanid'=>$id])->save($data);
    }
    //渲染安排车辆人员页面
    public function arrange(Request $request)
    {
        $this ->isLogin();//判断是否登录
        //获取前端页面传来的运单号
        $yundanid = $request ->param('id');
        $list = Dengji::paginate(10);
        $this ->view ->assign('list',$list);
        $this ->view ->assign('yundanid',$yundanid);
        return $this ->view ->fetch('user3_arrange');
    }
    public function delete($id)
    {
        //
    }
}
