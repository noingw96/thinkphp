<?php
namespace app\admin\controller;
use app\admin\common\Base;
class Index extends Base
{
    public function index()
    {
        $this ->isLogin();//判断是否登录
        return $this ->view ->fetch('index');//返回view文件夹下index的index.html
    }
}
