<?php
/**
 * Created by PhpStorm.
 * User: f
 * Date: 2018/11/20
 * Time: 23:14
 */

namespace app\index\controller;


use function base64_decode;
use function date;
use function dump;
use function input;
use function md5;
use function strtotime;
use think\Controller;
use think\Cookie;
use think\Db;
use think\Image;
use function time;

class User extends Controller
{

   public function user(){
       if(!Cookie::has('user')) {
            $this->error('你还没有登录',Url('Login/login'),2);
       }else{
//           $username=base64_decode(\cookie('username'));
           $uid=\cookie('user_id');
//           查询个人信息
           $userinfo=Db::name('user')->where('id',$uid )->find();
           $this->assign('userinfo',$userinfo);
//           查询发表文章
           $userart=Db::name('article')->where('author',$userinfo['name'])->paginate(6);
           $this->assign('userart',$userart);
//           查询订单信息
           $userindent=Db::name('indent')->where('user',$userinfo['name'])->paginate(6);
           $this->assign('userindent',$userindent);
//           查看留言
           $usermsg=Db::name('message')->where('user',\cookie('user'))->paginate(6);
           $this->assign('usermsg',$usermsg);
//           $userinfo=Db::table('user')
//               ->alias('u')
//               ->join('article a','u.name=a.author')
//               ->join('indent i','u.name=i.user')
//              ->select();
       }
       return $this->fetch('user');
   }
//   编辑邮箱和真实姓名
   public function edit_email(){
       $eamil=input('post.email');
       $realname=input('realname');
       $res=Db::name('user')->update(['email'=>$eamil,'realname'=>$realname,'id'=>\cookie('user_id')]);
       if($res>0){
           $this->success('修改信息成功');
       }
       else{
           $this->error('对不起，页面错误');
       }
   }
//   修改密码
   public function  edit_pwd(){
       $fpwd=Db::name('user')->where('id',\cookie('user_id'))->value('password');
       $pwd=md5(input('post.fpassword'));
       if($pwd==$fpwd){
           if (input('post.password')==input('post.repassword')){
               $res=Db::name('user')->update(['password'=>md5(input('post.password')),'id'=>\cookie('user_id')]);
               if($res>0){
                   $this->success('密码修改成功,即将重新登录','Login/login');
               }else{
                   $this->error('修改失败，请重新输入');
               }
           }
           else{
               $this->error('两次输入密码不对');
           }
       }else{
           $this->error('原密码不正确');
       }
   }

//   写攻略
  public function add_article(){
      $data=[
          'title'=>input('post.title'),
          'leibie'=>'攻略',
          'content'=>input('post.content'),
          'author'=>\cookie('user'),
          'createtime'=>date('Y-m-d H:i:s'),
      ];
//       图片处理
          $file = request()->file('image');
//             // 移动到框架应用根目录/uploads/ 目录下
          $info = $file->move('../uploads');
          if ($info) {
              $imapath = '/lvtu/uploads/' . $info->getSaveName();
              $image = \think\Image::open($file);
              $image->thumb(319, 179, \think\Image::THUMB_FIXED)->save($_SERVER['DOCUMENT_ROOT'] . $imapath);
              $data['pic'] = $imapath;
          }
          $res=Db::name('article')->insert($data);
          if($res>0){
              $this->success('发布攻略成功');
          }else{
              $this->error('页面错误，请重试');
          }
  }
    public function add_route(){
        $data=[
            'title'=>input('post.title'),
            'key'=>input('post.key'),
            'goal'=>input('post.goal'),
            'tfmz'=>input('post.tfmz'),
            'diyu'=>input('post.diyu'),
            'content'=>input('post.content'),
            'zt'=>'未审核'
        ];
//       图片处理
        $file = request()->file('image');
//             // 移动到框架应用根目录/uploads/ 目录下
        $info = $file->move('../uploads');
        if ($info) {
            $imapath = '/lvtu/uploads/' . $info->getSaveName();
            $image = \think\Image::open($file);
            $image->thumb(319, 179, \think\Image::THUMB_FIXED)->save($_SERVER['DOCUMENT_ROOT'] . $imapath);
            $data['pic'] = $imapath;
        }
        $res=Db::name('route')->insert($data);
        if($res>0){
            $this->success('发布路线成功，等待管理员审核');
        }else{
            $this->error('页面错误，请重试');
        }
    }
}