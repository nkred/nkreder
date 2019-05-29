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

class Goodsclass extends Controller
{
    public function index(){
        $data = Db::name('goodsclass')->select();
//        $data = classsorting($data);.
        $this->assign('data',$data);
        return view();
    }
    public function add(){
        if(request()->isPost()){
            $data = input('post.');
            $addId = Db::name('goodsclass')->insert($data);
            !$addId && $this->error('添加失败');
            $this->success('添加成功','index');
        }else{
            $group = Db::name('goodsclass')->select();
            $this->assign('group',$group);
            return view();
        }
    }
    public  function save(){
        if(request()->isPost()){
            $data = input('post.');
            $saveId = Db::name('goodsclass')->where('id',$data['id'])->update($data);
            !$saveId && $this->error('修改失败');
            $this->success('修改成功','index');
        }else{
            $id = input('id');
            $data = Db::name('goodsclass')->where('id',$id)->find();
            $group = Db::name('goodsclass')->select();
            $this->assign('group',$group);
            $this->assign('data', $data);
            return view();
        }
    }
    public  function del(){
        $id = input('id');
        $delId=Db::name('goodsclass')->where('id',$id)->delete();
        if ($delId){
            return ajaxmsg('删除成功',1);
        }else{
            return ajaxmsg('删除失败',0);
        }
    }
}