<?php
/**
 * Created by PhpStorm.
 * User: f
 * Date: 2018/9/18
 * Time: 17:13
 */

namespace app\admin\controller;


use function date;
use function date_get_last_errors;
use function dump;
use function input;
use function request;
use think\Controller;
use think\Db;
use app\admin\model\Article as ArticleModel;
use think\Request;

class Article extends Controller
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
//    展示文章
     public function article(){
          $list1=Db::name('article')->where('id','>',0)->select();
         $this->assign('list1',$list1);
         return $this->fetch('article');

     }
//     添加文章
     public function add_article(){
         return $this->fetch('add_article');
     }
//     编辑文章
     public function edit_article(){
         $articlelist=ArticleModel::where('id',input('id'))->find();
         $this->assign("articlelist",$articlelist);
         return $this->fetch('edit_article');
     }
//操作
    public function get_artdata(){
        $article=new ArticleModel();
        $data=$article::all();
        return $data;
    }
//     添加
     public function add_content(){
         $article=new ArticleModel();
         if(request()->isPost()){
             $data=input('post.');
             $data['createtime']=date('Y-m-d H:i:s');
             $article::get(input('id'));
             $result=$article->save($data);
                 if($result){
                     $this->success('添加文章成功','Article/article');
                 }
                 else{
                     $this->error('添加文章失败');
                 }
             }else{
                 $this->error('图片上传失败');
             }
         }
//     删除
     public function del_content(){
        if(ArticleModel::destroy(input('id'))){
            $this->success('删除成功','Article/article');
        }
        else{
            $this->error('操作失败');
        }
    }
//    编辑
      public function edit_content(){
       if(request()->isPost()){
               $data=input('post.');
               $data['createtime']=date('Y-m-d H:i:s');
               $art=ArticleModel::get(input('id'));
               if($art->save($data)){
                   $this->success('修改文章成功','Article/article');
               }
               else{
                   $this->error('修改文章失败');
               }
           }else{
               $this->error('图片上传失败');
           }

      }
}