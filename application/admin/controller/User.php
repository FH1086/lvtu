<?php
/**
 * Created by PhpStorm.
 * User: f
 * Date: 2018/9/16
 * Time: 9:18
 */

namespace app\admin\controller;


use function dump;
use function input;
use function md5;
use function print_r;
use think\Controller;
use think\Db;
use think\Session;
use think\Url;
use think\Request;
use app\admin\model\User as AdminUser;

class User extends Controller
{
//权限判断
    public function _initialize()
    {
        $aid = $_SESSION['think']['uid'];
        $auth = new Auth();
        $request = Request::instance();

        $au = $auth->check( $request->controller() . '/' . $request->action(), $aid);
        if (!$au) {// 第一个参数是规则名称,第二个参数是用户UID
            /* return array('status'=>'error','msg'=>'有权限！');*/
            $this->error('你没有权限');
        }
    }
// 模板开始
//    管理员列表
    public function admin(){
      //  $list=Db::name('admin_user')->where('id','>',0)->paginate(6);
        $list=AdminUser::where('id','>',0)->order('id','asc')->paginate(7);
        $this->assign('list',$list);
        return $this->fetch('admin');
     }
//     添加管理员
    public function add(){
        return $this->fetch('add');
    }
//    用户列表
    public function user(){
        $list=Db::name('user')->where('id','>',0)->paginate(10);
        $this->assign('list',$list);
        return $this->fetch('user');
    }
//    编辑管理员
    public function  edit(){
        $list=AdminUser::where('id',input('id'))->find();
        $this->assign('list',$list);
        return $this->fetch('edit');
    }


// 模板结束
public function uid($qx){

    $Id='';
    if($qx=='超级管理员'){
        $Id=16;
    }
    if($qx=='路线管理员'){
        $Id=17;
    }
    if($qx=='文章管理员'){
        $Id=14;
    }
    if($qx=='订单管理员'){
        $Id=20;
    }
    if($qx=='留言管理员'){
        $Id=19;
    }
    if($qx=='客栈管理员'){
        $Id=21;
    }
    return $Id;
}
//操作
    //添加管理员
   public function add_admin(){
        $user=$_POST['user'];
        $pwd=md5($_POST['pwd']);
       $qx=$_POST['qx'];
        $Id=$this->uid($qx);
        if($user!=null&&$pwd!=null&&$qx!=null){
                $data=array(
                'uid'=>$Id,
                'adminuser'=>$user,
                'adminpwd'=>$pwd,
                'qx'=>$qx,
            );
            $user=new AdminUser();
            $result=$user->save($data);
            if($result){
                $this->success('添加管理员成功','User/admin');
            }
            else{
                $this->error('未能成功添加管理员');
            }
        }
        else{
            $this->error('页面有数据空缺');
        }
   }
   //删除管理员
   public function del_admin(){
         $result=AdminUser::destroy(input('id'));
         if($result>0){
             $this->success('删除成功','User/admin');

         }
         else{
             $this->error('操作失败');
         }
   }
   //更新管理员
   public function edit_admin(){
        $adminuser=new AdminUser();
       $user=$_POST['user'];
       $pwd=md5($_POST['pwd']);
       $qx=$_POST['qx'];
       $Id=$this->uid($qx);
       $data=array('uid'=>$Id,'adminuser'=>$user,'adminpwd'=>$pwd,'qx'=>$qx);
       $adminuser=AdminUser::get(input('id'));
       $result=$adminuser->save($data);
       if($result>0){
           $this->success('更新成功','User/admin');

       }
       else{
           $this->error('更新失败');
       }
   }
//   删除用户
   public function del_user(){
    $result=Db::name('user')->where('id',input('id'))->delete();
    if($result>0){
        $this->success('删除成功','User/user');

    }
    else{
        $this->error('操作失败');
    }
}
}