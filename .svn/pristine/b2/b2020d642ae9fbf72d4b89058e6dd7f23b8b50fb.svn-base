<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2018/5/11
 * Time: 11:04
 */

namespace app\admin\validate;

use think\Validate;
class User extends Validate
{
    protected $rule=[
        'username'=>'require|max:25',
        'password'=>'require|max:18',
        'code' =>'require|captcha',
    ];
    protected $message=[
        'username'=>'用户名不能为空',
        'password'=>'密码也不能为空哦',
        'code' =>'请填写正确的验证码'
    ];
    protected $scene=[
        'login'=>['username','password','code'],
    ];


}