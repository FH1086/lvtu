<?php
/**
 * Created by PhpStorm.
 * User: f
 * Date: 2018/11/20
 * Time: 15:31
 */

namespace app\index\controller;


use app\admin\controller\User;
use function cookie;
use function dump;
use function input;
use think\Controller;
use think\Db;
use think\Session;
use think\Cookie;

class Message extends Controller
{
  public function message(){
      if(!Cookie::has('user')) {
            $this->error('你还没有登录',Url('Login/login'),2);
          $this->assign('user','');
      }
      else{
          $user=$_COOKIE['user'];
          $this->assign('user',$user);
      }
      return $this->fetch('message');
  }
  public function add(){
//      检测是否已经登录
      if(!Cookie::has('user')) {
            $this->error('你还没有登录',Url('Login/login'),2);
      }
      else{
//          留言数据
          $data=[
              'user'=>cookie('user'),
              'email'=>input('post.email'),
              'msg'=>input('post.msg'),
              'creattime'=>date('Y-m-d H:i:s'),
          ];
          $res=Db::name('message')->insert($data);
          if($res){
              $this->success('留言成功','Message/message');
          }else{
              $this->error('页面错误','Message/message');
          }
      }
  }

    public function del_message(){
        $result=Db::name('message')->where('id','=',input('id'))->delete();
        if($result){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }
}