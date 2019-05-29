<?php
namespace app\admin\controller;
/*
 * 登陆操作
 */
use think\Controller;
use think\Db;
use think\Session;
class Login extends Controller{
    //登录页面
    public function index(){
        return view();
    }
    //登录操作
    public function login()
    {
        if (request()->isPost()) {
            $data = input('post.');
//            $result = $this->validate($data, 'User.login');
//            if (true !== $result) {
//                $this->error($result);
//            } else {
                $list = db('admin')
                    ->where('username', $data['username'])
                    ->find();
                if (empty($list)) {
                    $this->error('用户名不存在');
                }
                if ($list['password'] != md5($data['password'])) {
                    $this->error('密码错误');
                }
//                            $time=time()+888;
                Session::set('amanageInfo', $list);
//                            Session::set('time',$time);
                $this->redirect('Index/index');
//                            $this->success('添加成功','Index/index');
            }
        }
//    }
}

?>