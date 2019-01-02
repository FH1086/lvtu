<?php
namespace app\index\controller;
use function convert_cyr_string;
use function dump;
use function input;
use function ldap_dn2ufn;
use think\Controller;
use think\Cookie;
use think\Db;
use think\View;


class Index extends Controller
{
    public function index()
    {
        if(!Cookie::has('user')) {
//            $this->error('你还没有登录',Url('Login/login'),2);
            $this->assign('user','');
        }
        else{
            $user=$_COOKIE['user'];
            $this->assign('user',$user);
        }
      $this->data();
      return $this->fetch('index');
    }
//主页展示的部分数据
    public function data(){
//        路线数据
        $ludata=Db::name('route')->where('zt','=','已审核')->select();
        $this->assign('ludata',$ludata);
//        新闻数据
        $news=Db::name('article')->where('leibie','=','新闻')->order('createtime','desc')->select();
        $this->assign('news',$news);
//        攻略数据
        $gonglue=Db::name('article')->where('leibie','=','攻略')->order('createtime','desc')->select();
        $this->assign('gonglue',$gonglue);
//        客栈数据
        $hotel=Db::name('hotel')->where('zt','=','已审核')->select();
        $this->assign('hotel',$hotel);

    }
//    public function detail(){
////        dump(input('id'));die();
//        $id=input('id');
//        $condition=input('condition');
//        $data=Db::name($condition)->where('id',$id)->find();
//        $this->assign('data',$data);
//        dump($data);die();
//        return \view('detail');
//    }
}
