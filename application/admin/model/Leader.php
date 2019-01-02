<?php
/**
 * Created by PhpStorm.
 * User: f
 * Date: 2018/10/16
 * Time: 15:23
 */

namespace app\admin\model;


use think\Model;
use function unlink;

class Leader extends Model
{
    protected $table='leader';
    public static function init()
    {
//        插入操作前的事件
        Leader::event('before_insert', function ($leader) {
            if($_FILES['image']['tmp_name']){
                $file = request()->file('image');
//             // 移动到框架应用根目录/uploads/ 目录下
                $info = $file->move( '../uploads');
                if($info){
                    $imapath='/lvtu/uploads/'.$info->getSaveName();
                    $image=\think\Image::open($file);
                    $image->thumb(319,179,\think\Image::THUMB_FIXED)->save($_SERVER['DOCUMENT_ROOT'] .$imapath);
                    $leader['pic']=$imapath;
                }
            }

        });
//        更新前操作
        Leader::event('before_update', function ($article) {
            if($_FILES['image']['tmp_name']) {
                $arts = Leader::find(input('id'));
                $picpath = $_SERVER['DOCUMENT_ROOT'].$arts['pic'];
//                dump($picpath);die();
                if (file_exists($picpath)) {
                    @unlink($picpath);
                } else {
                    echo '路径错误';
                }
                $file = request()->file('image');
                // 移动到框架应用根目录/uploads/ 目录下
                $info = $file->move('../uploads');
                if ($info) {
                    $imapath='/lvtu/uploads/'.$info->getSaveName();
                    $image=\think\Image::open($file);
                    $image->thumb(319,179,\think\Image::THUMB_FIXED)->save($_SERVER['DOCUMENT_ROOT'] .$imapath);
                    $article['pic'] = $imapath;
                }
            }
        });
//        删除前操作
        Leader::event('before_delete', function ($leader) {
            $leader = Leader::find(input('id'));
            $picpath = $_SERVER['DOCUMENT_ROOT'] . $leader['pic'];
            if (file_exists($picpath)) {
                @unlink($picpath);
            } else {
                echo '路径错误';
            }

        });
    }

}