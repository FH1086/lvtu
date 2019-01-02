<?php
/**
 * Created by PhpStorm.
 * User: f
 * Date: 2018/7/4
 * Time: 18:18
 */

namespace app\index\controller;
use function base64_encode;
use function dump;
use function session;
use think\Controller;
use think\Cookie;
use think\Db;
use think\Session;


class Login extends Controller
{
   //登录页面视图
    public function login(){
     return $this->fetch('login');
    }

    //登录页面操作
    public function loginaction(){
        $username=$_POST['username'];
        $pwd=md5($_POST['password']);
        //判断是否为空
        if(empty($username)){
            $this->error('用户名不能为空');
        }
        if(empty($pwd)){
            $this->error('用户名不能为空');
        }
        //验证用户名密码
        $i=db('user')->where('username',$username)->find();
        if(empty($i)){
            $this->error('用户名或者密码错误');
        }
        if($i['password']!=$pwd){
            $this->error('用户名或者密码错误');
        }
        //记录用户信息
        \cookie('user_id',$i['id'],3600);
        \cookie('user',$i['name'],3600);
        \cookie('realuser',base64_encode($i['realname']),3600);
        \cookie('username',base64_encode($i['username']),3600);//一小时内有效
        //登录成功跳转
        $this->redirect(Url('Index/index'));
    }

    //注册页面视图
    public function register(){
        return $this->fetch('register');
    }
    public function logout(){
        Cookie::delete('user_id');
        Cookie::delete('user');
        Cookie::delete('realuser');
        Cookie::delete('username');
        $this->redirect(Url('Index/index'));
    }
}