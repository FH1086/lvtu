<?php
/**
 * Created by PhpStorm.
 * User: f
 * Date: 2018/11/19
 * Time: 16:09
 */

namespace app\index\controller;


use function date;
use function dump;
use function input;
use think\Controller;
use think\Db;

class Mz extends Controller
{
    public function mz(){
        $data=Db::name('mz')->where('id','>',0)->paginate(12);
        $this->assign('data',$data);
        return $this->fetch('mz');
    }
    public function detail_mz(){
        $data=Db::name(input('condition'))->where('id',input('id'))->find();
        $this->assign('data',$data);
        return $this->fetch('detail_mz');
    }
    public function serch(){
        $key='%'.input('key').'%';
        $data=Db::name('mz')->where('name','like',$key)->paginate(9);
        $this->assign('data',$data);
        $this->assign('key',input('key'));
        return $this->fetch('serch');
    }

}