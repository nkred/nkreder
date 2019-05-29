<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2018/5/10
 * Time: 15:13
 */

namespace app\admin\controller;

/*
 * 分组管理
 */
use think\Controller;
use think\Db;

class Students extends Controller
{
    public function index(){
        $data = Db::name('student')->where('is_register',1)->select();
        $this->assign('data',$data);
        return view();
    }
    public  function save(){
        if(request()->isPost()){
            $data = input('post.');
            $saveId = Db::name('student')->where('id',$data['id'])->update($data);
            !$saveId && $this->error('修改失败');
            $this->redirect('index');
        }else{
            $id = input('id');
            $data = Db::name('student')->where('id',$id)->find();
            $this->assign('data', $data);
            return view();
        }
    }
}