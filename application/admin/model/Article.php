<?php
/**
 * Created by PhpStorm.
 * User: f
 * Date: 2018/9/26
 * Time: 15:27
 */

namespace app\admin\model;


use function dump;
use function file_exists;
use function input;
use function pi;
use think\Model;
use function unlink;

class Article extends Model
{
    protected $table='article';
    public static function init()
    {
//        插入操作前的事件
        Article::event('before_insert', function ($article) {
            if($_FILES['image']['tmp_name']) {
                $file = request()->file('image');
//             // 移动到框架应用根目录/uploads/ 目录下
                $info = $file->move('../uploads');
                if ($info) {
                    $imapath='/lvtu/uploads/'.$info->getSaveName();
                    $image=\think\Image::open($file);
                    $image->thumb(319,179,\think\Image::THUMB_FIXED)->save($_SERVER['DOCUMENT_ROOT'] .$imapath);
                    $article['pic'] = $imapath;
                }
            }
        });
//        更新前操作
        Article::event('before_update', function ($article) {
            if($_FILES['image']['tmp_name']) {
                $arts = Article::find(input('id'));
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
        Article::event('before_delete', function ($article) {
            $arts=Article::find(input('id'));
            $picpath=$_SERVER['DOCUMENT_ROOT'].$arts['pic'];
            if(file_exists($picpath)){
                @unlink($picpath);
            }else{
                echo '路径错误';
            }
        });
    }

}