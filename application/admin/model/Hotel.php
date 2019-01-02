<?php
/**
 * Created by PhpStorm.
 * User: f
 * Date: 2018/10/12
 * Time: 14:53
 */

namespace app\admin\model;


use function file_exists;
use function func_get_args;
use function input;
use function request;
use think\Model;
use function unlink;

class Hotel extends Model
{
   protected $table='hotel';
   public static function init()
   {
      Hotel::event('before_insert',function ($hotel){
          if($_FILES['image']['tmp_name']){
              $file=request()->file('image');
              $info=$file->move('../uploads');
              if($info){
                  $imapath='/lvtu/uploads/'.$info->getSaveName();
                  $image=\think\Image::open($file);
                  $image->thumb(319,179,\think\Image::THUMB_FIXED)->save($_SERVER['DOCUMENT_ROOT'] .$imapath);
                  $hotel['pic']=$imapath;
              }
          }
      });
       Hotel::event('before_update',function ($hotel){
           if($_FILES['image']['tmp_name']){
               $ho=Hotel::get(input('id'));
               $file_path= $_SERVER['DOCUMENT_ROOT'] .$ho['pic'];
               if(file_exists($file_path)){
                   @unlink($file_path);
               }else{
                   echo '路径cuowu';
               }
               $file=request()->file('image');
               $info=$file->move('../uploads');
               if($info){
                   $imapath='/lvtu/uploads/'.$info->getSaveName();
                   $image=\think\Image::open($file);
                   $image->thumb(319,179,\think\Image::THUMB_FIXED)->save($_SERVER['DOCUMENT_ROOT'] .$imapath);
                   $hotel['pic']=$imapath;
               }
           }
       });
       Hotel::event('before_delete',function ($hotel){
               $ho=Hotel::get(input('id'));
               $file_path= $_SERVER['DOCUMENT_ROOT'] . $ho['pic'];
               if(file_exists($file_path)){
                   @unlink($file_path);
               }else{
                   echo '路径错误';
               }

       });
   }
}