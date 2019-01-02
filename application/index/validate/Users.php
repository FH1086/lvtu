<?php
/**
 * Created by PhpStorm.
 * User: f
 * Date: 2018/7/24
 * Time: 16:30
 */
namespace app\index\validate;
use think\Validate;
class Users extends Validate
{
protected $rule=[
    'username'=>'require|length:8,11|unique:user',
    'name'=>'require|min:2|chsAlpha|unique:user',
    'realname'=>'require|min:2|chsAlpha',
    'password'=>'require|confirm',
    'email'=>'email',
    'sex'=>'require',
    'checkbox'=>'require',
];
protected $message=[
    'username.require'=>'账号不为空',
    'username.length'=>'手机号长度为8-11位',
    'username.unique'=>'手机号已存在',
    'name.require'=>'昵称不能为空',
    'name.min'=>'昵称至少两个字符',
    'name.chsAlpha'=>'昵称必须为汉字或字母',
    'name.unique'=>'昵称已存在',
    'realname.require'=>'真实姓名不能为空',
    'realname.min'=>'真实姓名至少两个字符',
    'realname.chsAlpha'=>'真实姓名必须为汉字或字母',
    'password.require'=>'密码不能为空',
    'password.confirm'=>'两次密码不一致',
    'email.email'=>'邮箱格式错误',
    'sex.require'=>'性别必须',
    'checkbox.require'=>'必须同意隐私政策',
];



}