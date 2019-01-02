<?php
/**
 * Created by PhpStorm.
 * User: f
 * Date: 2018/9/28
 * Time: 22:05
 */

namespace app\admin\controller;


use function date_get_last_errors;
use function dump;
use function input;
use function request;
use think\Controller;
use think\Db;
use app\admin\model\Route as RouteModel;
use think\Request;
use function time;


class Route extends Controller
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
    //路线列表显示
   public function route(){
       $route=Db::name('route')->where('id','>',0)->select();
       $this->assign('route',$route);
       $count=Db::name('route')->where('zt','未审核')->count();
       $this->assign('count',$count);
      return $this->fetch('route');
   }
   //添加路线
   public function add_route(){
       return $this->fetch('add_route');
   }
//   编辑展示
   public function edit_list(){
       $list=RouteModel::where('id',input('id'))->find();
       $this->assign('list',$list);
       return $this->fetch('edit_route');
   }


//添加
   public function add_action(){
       $route=new RouteModel();
       if(request()->isPost()){
           $data=input('post.');
          if($route->save($data)){
              $this->success('添加路线成功','Route/route');
          }
          else{
              $this->error('添加失败');
          }
       }
   }
//   删除
    public function del_action(){
        $route=new RouteModel();
            if($route::destroy(input('id'))){
                $this->success('删除成功','Route/route');
            }
            else{
                $this->error('删除失败');
            }
    }
//    编辑
    public function edit_action(){
        $route=new RouteModel();
        $data=input('post.');
         $result=$route::get(input('id'));
         if($result->save($data)){
             $this->success('编辑成功','Route/route');
         }else{
             $this->error('编辑失败');
         }
    }


}