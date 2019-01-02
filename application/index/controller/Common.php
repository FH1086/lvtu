<?php
/**
 * Created by PhpStorm.
 * User: f
 * Date: 2018/11/2
 * Time: 16:33
 */

namespace app\index\controller;


use function base64_decode;
use function date;
use function dump;
use function implode;
use function input;
use function is_uploaded_file;
use function rand;
use think\Controller;
use think\Cookie;
use think\Db;
use function time;

class Common extends Controller
{
    public function indexheader(){
        if(!Cookie::has('user')) {
//            $this->error('你还没有登录',Url('Login/login'),2);
            $this->assign('user','');
        }
        else{
            $user=$_COOKIE['user'];
            $this->assign('user',$user);
        }
        return $this->fetch('common/indexheader');
    }
    //页脚视图
    public function foot(){
        return $this->fetch('common/foot');
    }
    //页头视图
    public function header(){
        return $this->fetch('common/header');
    }
    // 预订
    public function yuding(){
        if(Cookie::has('user')){
          $id=input('id');
          $name=input('name');
          $arr=Db::name($name)->where('id',$id)->find();
          $data=[
              'number'=>time().rand(10,1000),//以时间错和随机数为外部编号
              'ydtime'=>date('Y-m-d H:i:s'),
              'content'=>$arr['name'],
              'leibie'=>input('leibie'),
              'uid'=>\cookie('user_id'),
              'user'=>\cookie('user'),
              'utel'=>base64_decode(\cookie('username')),
              'tel'=>$arr['tel'],
              'zt'=>'未支付'
          ];
          $res=Db::name('indent')->insert($data);
          if($res>0){
              $this->success('您已成功预订');
          }else{
              $this->error('预订失败，请重新操作');
          }
        }
        else{
            $this->error('您需要登录才可进行操作，请先登录','Login/login');
        }

    }
    //退订
    public function tuiding(){
//        获取订单信息
        $indentinfo=Db::name('indent')->where('id',input('id'))->find();
        $time=$indentinfo['ydtime'];//预订时间
        $now_time = date("Y-m-d H:i:s",time());//当前时间
        $f=strtotime($now_time)-strtotime($time);//时间差
        if($f<24*60*60){//预订时间24小时内可退订
            $res=Db::name('indent')->where('id',input('id'))->update(['zt'=>'已取消']);
            if($res>0){
                $this->redirect('User/user');
            }
            else{
                $this->error('退订失败，请联系管理员');
            }
        }
        else{
            $this->error("对不起，订单已失效或是订单正在生效无法退订，请联系管理员");
        }

    }
    //支付
    public function zhifu(){
        $res=Db::name('indent')->where('id',input('id'))->update(['zt'=>'已支付', 'ydtime'=>date('Y-m-d H:i:s')]);
        if($res>0){
            $this->redirect('User/user');
        }
        else{
            $this->error('支付失败，请联系管理员');
        }
    }
//    删除
    public function del(){
        $result=Db::name(input('condition'))->where('id','=',input('id'))->delete();
        if($result){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }
    public function serch(){
        $c=input('post.condition');
        switch ($c){
            case '民族':
                $condition='mz';
                return $this->redirect('Mz/serch',['key'=>input('post.key')]);
                break;
            case '客栈':
                $condition='hotel';
                return $this->redirect('Hotel/serch',['key'=>input('post.key')]);
                break;
            case '路线':
            $condition='route';
                return $this->redirect('Route/serch',['key'=>input('post.key')]);
                break;
            case '资讯':
                $condition='article';
                return $this->redirect('Article/serch_art',['key'=>input('post.key')]);
                break;
            case '攻略':
                $condition='article';
                return $this->redirect('Article/serch_st',['key'=>input('post.key')]);
                break;
            case '向导':
                $condition='leader';
                return $this->redirect('Leader/serch',['key'=>input('post.key')]);
                break;
        }
//        return $this->fetch('/'.$condition.'/serch');
    }

}