<?php
/**
 * Created by PhpStorm.
 * User: f
 * Date: 2018/10/6
 * Time: 21:29
 */

namespace app\admin\model;


use function dump;
use think\Model;
use function unlink;

class System extends Model
{
    protected  $table='mz';
    public static function init()
    {
//        插入操作前的事件
        System::event('before_insert', function ($mz) {
            if($_FILES['image']['tmp_name']){
                $file = request()->file('image');
//             // 移动到框架应用根目录/uploads/ 目录下
                $info = $file->move( '../uploads');
                if($info){
                    $imapath='/lvtu/uploads/'.$info->getSaveName();
                    $image=\think\Image::open($file);
                    $image->thumb(319,179,\think\Image::THUMB_FIXED)->save($_SERVER['DOCUMENT_ROOT'] .$imapath);
                    $mz['pic']=$imapath;
                }
            }

        });
//        更新前操作
        System::event('before_update', function ($mz) {
            if($_FILES['image']['tmp_name']) {
                $mzs = System::find(input('id'));
                $picpath = $_SERVER['DOCUMENT_ROOT'].$mzs['pic'];
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
                    $mz['pic'] = $imapath;
                }
            }
        });
//        删除前操作
        System::event('before_delete', function ($mz) {
            $mzs = System::find(input('id'));
            $picpath = $_SERVER['DOCUMENT_ROOT'] . $mzs['pic'];
            if (file_exists($picpath)) {
                @unlink($picpath);
            } else {
                echo '路径错误';
            }

        });
    }
}