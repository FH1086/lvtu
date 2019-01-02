<?php
/**
 * Created by PhpStorm.
 * User: f
 * Date: 2018/9/4
 * Time: 18:41
 */

namespace app\admin\controller;
use function md5;
use think\Controller;
use think\Session;
use think\Db;

class Index extends Controller
{
   public function Index(){
       if(Session::has('admin_user')){
           return $this->fetch('Adminindex');
       }
       else{
           $this->error('您还没有登录，请先登录','Login/login');
       }

   }
   public function changpwd(){
//       获取信息
      $npwd=$_POST['fpwd'];
      $pwd=$_POST['pwd'];
      $rpwd=$_POST['rpwd'];
       $adminuser=\session('admin_user');
//       验证原密码是否正确
      $fpwd=Db::name('admin_user')->where('adminuser', $adminuser)->value('adminpwd');
      if($fpwd==md5($npwd)){
          if($pwd!=$rpwd){
              $this->error('两次密码不一致','Index/Index');
          }
          else{
              if(Session::has('admin_user')){
                  //修改密码
                  $res=Db::name('admin_user')->where('adminuser', $adminuser)->data(['adminpwd' => md5($pwd)])->update();
                  if($res){
                      $this->success('修改密码成功,即将重新登录','Login/login');
                  }
                  else{
                      $this->redirect(Url('Index/index'));
                  }
              }
          }
      }
      else{
          $this->error('原密码错误，不能修改');
      }

   }


}