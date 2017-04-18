<?php
namespace Admin\Controller;

use Model\UserModel;
use Think\Controller;

class LoginController extends Controller
{
    public function login()       //登陆系统  两个逻辑，展示页面 和 收集表单进行验证
    {
        if (!empty($_POST)) {
            $user = new UserModel();//在Model里面写一个专门的方法进行验证
            $info = $user->checkNamePwd($_POST['supername'], $_POST['password']);
            if ($info) {//如果用户名或者密码正确则进入
                $utype = $info['utype'];    //判断用户类型 1：后台管理员 否则没权限
                if ($utype == 1) {
                    //登陆信息持久化session
                    session('supername', $info['username']);
                    session('superid', $info['uid']);
                    //进行跳转到index页面
                    $this->redirect('Shop/index');
                } else{
                        $this->error('你没有权限登陆','',1);
                    }
            }else {
                $this->error('用户名或密码错误','',2);
            }
        } else {
            $this->display();
        }
    }

    public function logout()      //退出系统
    {
        session_destroy();
        $this->redirect("Login/login");
    }
}