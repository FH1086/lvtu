<?php
/**
 * Created by PhpStorm.
 * User: f
 * Date: 2018/9/16
 * Time: 9:17
 */

namespace app\admin\controller;


use const COUCHBASE_EMPTY_KEY;
use function date;
use function input;
use function readline_add_history;
use think\Controller;
use think\Db;
use think\Request;

class Message extends Controller
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

    public function message(){
        $list=Db::name('message')->where('id','>',0)->select();
        $this->assign('list',$list);
        $count=Db::name('message')->where(['respone'=>'','responetime'=>''])->count();
        $this->assign('count',$count);
        return $this->fetch('message/message');
    }
    public function respone(){
        $list=Db::name('message')->where('id',input('id'))->find();
        $this->assign('list',$list);
        return $this->fetch('respone');
    }



    public function del_message(){
        $result=Db::name('message')->where('id','=',input('id'))->delete();
        if($result){
            $this->success('删除成功','Message/message');
        }else{
            $this->error('删除失败');
        }
    }
    public function respone_message(){

        $result=Db::name('message')->where('id',input('id'))->setField(['respone'=>input('post.respone'),'responetime'=>date('Y-m-d H:i:s')]);
        if($result>0){
            $this->success('回复成功','Message/message');
        }
        else{
            $this->error('回复失败请重新操作');
        }
    }
}