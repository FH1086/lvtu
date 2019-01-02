<?php
/**
 * Created by PhpStorm.
 * User: f
 * Date: 2018/10/6
 * Time: 15:40
 */

namespace app\admin\controller;



use function date_get_last_errors;
use function dump;
use function input;
use function print_r;
use function request;
use think\Controller;
use think\Db;
use app\admin\model\Sytem;
use app\admin\model\Leader as LeaderModel;
use think\Request;


class System extends Controller
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
//    民族视图
    public function mz(){
        $list=Db::name('mz')->where('id','>',0)->paginate(10);
        $this->assign('list',$list);
        return $this->fetch('mz');
    }
    public function add_mz(){
        return $this->fetch('add_mz');
    }
    public function edit_mz(){
        $data=Db::name('mz')->where('id',input('id'))->find();
        $this->assign('data',$data);
        return $this->fetch('edit_mz');
    }
//    向导视图
     public function leader(){
        $leaderlist=Db::name('leader')->where('id','>',0)->paginate(10);
        $this->assign('list',$leaderlist);
        return $this->fetch('leader');
     }
     public function add_leader(){
        return $this->fetch('add_leader');
    }
    public function edit_leader(){
        $leaderdata=Db::name('leader')->where('id',input('id'))->find();
        $this->assign('leaderdata',$leaderdata);
        return $this->fetch('edit_leader');
    }



//民族操作
    public function add_mzcontent(){
        $mz=new \app\admin\model\System();
        if(request()->isPost()){
            $data=input('post.');
            if($mz->save($data)){
                $this->success('添加成功','System/mz');
            }
            else{
                $this->error('添加失败');
            }
        }
    }
    public function del_mz(){
        $mz=new \app\admin\model\System();
        if($mz::destroy(input('id'))){
            $this->success('删除成功','System/mz');
        }else{
            $this->error('删除失败');
        }
    }
    public function edit_mzcontent(){
        $mz=new \app\admin\model\System();
        $data=input('post.');
        $result=$mz::get(input('id'));
        if($result->save($data)){
            $this->success('编辑成功','System/mz');
        }else{
            $this->error('编辑失败');
        }
    }
//向导操作
    public function add_leaderdata(){
        $leader=new LeaderModel();
        if(request()->isPost()){
            $data=input('post.');
            if($leader->save($data)){
                $this->success("添加向导成功",'System/leader');
            }else{
                $this->error('添加向导失败');
            }
        }
    }
    public function del_leaderdata(){
        $leader=new LeaderModel();
        if($leader::destroy(input('id'))){
            $this->success('删除成功','System/leader');
        }else{
            $this->error('删除向导失败');
        }
    }
    public function edit_leaderdata(){
        $leader=new LeaderModel();
        $data=input('post.');
        $re=$leader::get(input('id'));
        if($re->save($data)){
            $this->success('编辑向导成功','System/leader');
        }
        else{
            $this->error('编辑向导失败');
        }
    }

}