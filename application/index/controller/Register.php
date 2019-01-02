<?php
/**
 * Created by PhpStorm.
 * User: f
 * Date: 2018/7/24
 * Time: 17:08
 */

namespace app\index\controller;
use think\Controller;
use think\Db;
use app\index\validate\Users as UserValidate;
use think\Loader;

class Register extends Controller
{
    public function registeradd(){
        //获取页面提交数据
      $data=input('post.');
      $validate=Loader::validate('Users');
      //验证
      if(!$validate->check($data)){
            $this->error($validate->getError());
        }
      //整理页面提交的数据
      $post_data=array(
          'username'=>$_POST['username'],
          'name'=>$_POST['name'],
          'realname'=>$_POST['realname'],
          'password'=>md5($_POST['password']),
          'email'=>$_POST['email'],
          'sex'=>$_POST['sex'],
);
    $true=Db::name('user')
        ->where('username',$_POST['username'])
        ->select();
    //判断是否已被注册
    if($true==null) {
        $id = Db::name('user')
            ->data($post_data)
            ->insert();
        if ($id) {
            //插入数据成功后把用户昵称写入cookie
            cookie("user", $_POST['name'], 86400 * 30);
            $this->success('注册成功,将跳转到登录界面', 'Login/login');
        } else {
            $this->error('注册失败，请重试...');
        }
    }    else{
        return $this->error('该手机号已被注册！');
    }
    }
}