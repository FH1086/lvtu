<?php
/**
 * Created by PhpStorm.
 * User: f
 * Date: 2018/11/20
 * Time: 9:41
 */

namespace app\index\controller;


use function date;
use think\Controller;
use think\Db;

class Leader extends Controller
{
    public function leader(){
        $data=Db::name('leader')->where('id','>',0)->paginate(9);
        $this->assign('data',$data);
        return $this->fetch('leader');
    }
    public function detail_leader(){
        $data=Db::name(input('condition'))->where('id',input('id'))->find();
        $this->assign('data',$data);
        return $this->fetch('detail_leader');
    }
    public function serch(){
        $key='%'.input('key').'%';
        $data=Db::name('leader')->where('name','like',$key)->paginate(9);
        $this->assign('data',$data);
        $this->assign('key',input('key'));
        return $this->fetch('serch');
    }
}