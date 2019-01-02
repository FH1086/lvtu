<?php
/**
 * Created by PhpStorm.
 * User: f
 * Date: 2018/9/28
 * Time: 22:33
 */

namespace app\admin\model;


use function exif_thumbnail;
use function import;
use think\Loader;
use think\Model;
use uploads;


class Route extends Model
{
  protected $table='route';
    public static function init()
    {
//        插入操作前的事件
        Route::event('before_insert', function ($route) {
            if($_FILES['image']['tmp_name']){
                $file = request()->file('image');
//             // 移动到框架应用根目录/uploads/ 目录下
                $info = $file->move( '../uploads');
                if($info){
                    $imapath='/lvtu/uploads/'.$info->getSaveName();
                    $image=\think\Image::open($file);
                    $image->thumb(319,179,\think\Image::THUMB_FIXED)->save($_SERVER['DOCUMENT_ROOT'] .$imapath);
                    $route['pic']=$imapath;
                }
            }

        });
//        更新前操作
        Route::event('before_update', function ($route) {
            if($_FILES['image']['tmp_name']) {
                $rots = Route::find(input('id'));
                $picpath = $_SERVER['DOCUMENT_ROOT'] . $rots['pic'];
                if (file_exists($picpath)) {
                    @unlink($picpath);
                } else {
                    echo '路径错误';
                    die();
                }
                $file = request()->file('image');
                // 移动到框架应用根目录/uploads/ 目录下
                $info = $file->move('../uploads');
                if ($info) {
                    $imapath='/lvtu/uploads/'.$info->getSaveName();
                    $image=\think\Image::open($file);
                    $image->thumb(319,179,\think\Image::THUMB_FIXED)->save($_SERVER['DOCUMENT_ROOT'] .$imapath);
                    $route['pic']=$imapath;
                }
            }
        });
//        删除前操作
        Route::event('before_delete', function ($route) {
                $rots = Route::find(input('id'));
                $picpath = $_SERVER['DOCUMENT_ROOT']  . $rots['pic'];
                if (file_exists($picpath)) {
                    @unlink($picpath);
                } else {
                    echo '路径错误';
                }

        });
    }
}