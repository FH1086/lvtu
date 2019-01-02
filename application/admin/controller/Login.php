<?php
/**
 * Created by PhpStorm.
 * User: f
 * Date: 2018/9/4
 * Time: 16:09
 */
namespace app\admin\controller;
use function dump;
use think\Controller;
use think\Session;

class Login extends Controller
{

    public function login(){
        return $this->fetch('login');
    }

//    登录操作
    public function loginaction(){
        //获取页面提交的账号和密码
      $adminuser=$_POST['adminuser'];
      $adminpwd=md5($_POST['adminpwd']);
      //判断账号和密码是否正确
        if($adminuser!=''&&$adminpwd!='')
        {
            $i=db('admin_user')->where('adminuser',$adminuser)->find();
            if(empty($i)){
                $this->error('用户名或者密码错误');
            }
            if($i['adminpwd']!=$adminpwd){
                $this->error('用户名或者密码错误');
            }
        }
        else{
            $this->error('账号和密码不能为空');
        }
        Session::set('uid',$i['uid']);
        Session::set('admin_user',$adminuser);
        //成功后跳转
        $this->redirect(Url('Index/index'));
    }
    public function logout(){
        Session::delete('admin_user');
        $this->redirect(Url('Login/login'));
    }
}