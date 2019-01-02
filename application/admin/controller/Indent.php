<?php
/**
 * Created by PhpStorm.
 * User: f
 * Date: 2018/10/6
 * Time: 15:36
 */

namespace app\admin\controller;


use function inet_pton;
use function input;
use function mysql_thread_id;
use think\Controller;
use think\Db;
use think\Request;

class Indent extends Controller
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

    public function indent(){
        $list=Db::name('indent')->where('id','>',0)->select();
        $this->assign('list',$list);
        $count=Db::name('indent')->where('zt','未支付')->count();
        $this->assign('count',$count);
        return $this->fetch('indent');
    }
    public function edit_indent(){
        $data=Db::name('indent')->where('id',input('id'))->find();
        $this->assign('data',$data);
       return $this->fetch('edit_indent');
    }

//
    public function edit_action(){
         $data=input('post.');
         $result=Db::name('indent')->where('id',input('id'))->update($data);
         if($result){
             $this->success('更新订单成功','Indent/indent');
         }else{
             $this->error('更新订单失败');
         }
    }
    public function del_action(){
        $result=Db::name('indent')->where('id',input('id'))->delete();
        if($result){
            $this->success('删除订单成功','Indent/indent');
        }else{
            $this->error('删除订单失败');
        }
    }
}