<?php
/**
 * Created by PhpStorm.
 * User: f
 * Date: 2018/10/12
 * Time: 14:53
 */

namespace app\admin\controller;


use function input;
use function request;
use think\Controller;
use think\Db;
use app\admin\model\Hotel as HotelModel;
use think\Request;

class Hotel extends Controller
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

   public function hotel(){
       $list=Db::name('hotel')->where('id','>',0)->select();
       $this->assign('list',$list);
       $count=Db::name('hotel')->where('zt','未审核')->count();
       $this->assign('count',$count);
       return $this->fetch('hotel');
   }
   public function add_hotel(){
       return $this->fetch('add_hotel');
   }
    public function edit_hotel(){
       $data=Db::name('hotel')->where('id',input('id'))->find();
       $this->assign('data',$data);
       return $this->fetch('edit_hotel');
    }
//操作
    public function add_action(){
      $hotel=new HotelModel();
      if(request()->isPost()){
          $data=input('post.');
          if($hotel->save($data)){
              $this->success('添加客栈成功','Hotel/hotel');
          }else{
              $this->error('添加失败');
          }
      }
    }
    public function edit_action(){
        if(request()->isPost()){
            $data=input('post.');
            $hotel=HotelModel::get(input('id'));
            if($hotel->save($data)){
                $this->success('编辑客栈成功','Hotel/hotel');
            }else{
                $this->error('编辑失败');
            }
        }
    }
    public function del_action(){
        if(HotelModel::destroy(input('id'))){
            $this->success('删除客栈成功','Hotel/hotel');
        }else{
            $this->error('删除客栈成功');
        }
    }



}