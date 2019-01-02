<?php
/**
 * Created by PhpStorm.
 * User: f
 * Date: 2018/11/3
 * Time: 10:29
 */

namespace app\index\controller;


use function dump;
use function input;
use think\Controller;
use think\Cookie;
use think\Db;
use function view;

class Hotel extends Controller
{
     public function hotel(){
         $data=Db::name('hotel')->where('zt','已审核')->paginate(12);
         $this->assign('data',$data);
         return $this->fetch('hotel');
     }
     public function detail_hotel(){
         $data=Db::name(input('condition'))->where('id',input('id'))->find();
         $this->assign('data',$data);
         return $this->fetch('detail_hotel');
     }
    public function serch(){
        $key='%'.input('key').'%';
        $data=Db::name('hotel')->where('name|address','like',$key)->paginate(9);
        $this->assign('data',$data);
        $this->assign('key',input('key'));
        return $this->fetch('serch');
    }


}