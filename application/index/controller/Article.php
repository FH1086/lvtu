<?php
/**
 * Created by PhpStorm.
 * User: f
 * Date: 2018/11/3
 * Time: 11:11
 */

namespace app\index\controller;


use function input;
use think\Controller;
use think\Db;

class Article extends Controller
{
    public function article(){
        $data=Db::name('article')->where('leibie','=','新闻')->paginate(6);
        $this->assign('data',$data);
        return $this->fetch('article');
    }
    public function detail_article(){
        $data=Db::name(input('condition'))->where('id',input('id'))->find();
        $this->assign('data',$data);
        return $this->fetch('detail_article');
    }
    public function serch_art(){
        $key='%'.input('key').'%';
        $data=Db::name('article')->where('leibie','=','新闻')->whereLike('title',$key)->paginate(9);
        $this->assign('data',$data);
        $this->assign('key',input('key'));
        return $this->fetch('serch_art');
    }
    public  function strategy(){
        $data=Db::name('article')->where('leibie','=','攻略')->paginate(12);
        $this->assign('data',$data);
        return $this->fetch('strategy');
    }
    public function detail_strategy(){
        $data=Db::name(input('condition'))->where('id',input('id'))->find();
        $this->assign('data',$data);
        return $this->fetch('detail_strategy');
    }
    public function serch_st(){
        $key='%'.input('key').'%';
        $data=Db::name('article')->where('leibie','=','攻略')->whereLike('title',$key)->paginate(9);
        $this->assign('data',$data);
        $this->assign('key',input('key'));
        return $this->fetch('serch_st');
    }
}