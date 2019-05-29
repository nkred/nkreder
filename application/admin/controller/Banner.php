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

class Banner extends Controller
{
    public function index(){
        $data = Db::name('banner')->select();
        $this->assign('data',$data);
        return view();
    }
    public function add(){
        if(request()->isPost()){
            $data = input('post.');
            $data['time'] = time();
            $addId = Db::name('banner')->insert($data);
            !$addId && $this->error('添加失败');
            $this->redirect('index');
        }else{
            return view();
        }
    }
    public  function save(){
        if(request()->isPost()){
            $data = input('post.');
            $saveId = Db::name('banner')->where('id',$data['id'])->update($data);
            !$saveId && $this->error('修改失败');
            $this->redirect('index');
        }else{
            $id = input('id');
            $data = Db::name('banner')->where('id',$id)->find();
            $this->assign('data', $data);
            return view();
        }
    }
    public  function del(){
        $id = input('id');
        $delId=Db::name('banner')->where('id',$id)->delete();
        if ($delId){
            return ajaxmsg('删除成功',1);
        }else{
            return ajaxmsg('删除失败',0);
        }
    }
}