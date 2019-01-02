<?php
/**
 * Created by PhpStorm.
 * User: f
 * Date: 2018/11/3
 * Time: 11:10
 */

namespace app\index\controller;


use think\Controller;
use think\Db;

class Route extends Controller
{
    public function route(){
        $data=Db::name('route')->where('zt','=','已审核')->paginate(12);
        $this->assign('data',$data);
        return $this->fetch('route');
    }
    public function detail_route(){
        $data=Db::name(input('condition'))->where('id',input('id'))->find();
        $this->assign('data',$data);
        return $this->fetch('detail_route');
    }
    public function serch(){
        $key='%'.input('key').'%';
        $data=Db::name('route')->where('title|key|goal|tfmz','like',$key)->paginate(9);
        $this->assign('data',$data);
        $this->assign('key',input('key'));
        return $this->fetch('serch');
    }
}