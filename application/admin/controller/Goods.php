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

class Goods extends Controller
{
    public function index(){
        $goods_id = input('goods_id');
        $data = Db::name('goods')->where('class',$goods_id)->select();
        $class = Db::name('goodsclass')->where('id',$goods_id)->find();
//        $data = classsorting($data);.
        $this->assign('data',$data);
        $this->assign('class',$class);
        $this->assign('class_id',$goods_id);
        return view();
    }
    public function add(){
        if(request()->isPost()){
            $data = input('post.');
            $data['img'] = uploadFile("file",140,140);
            $data['poster'] = uploadFile("file1",0,0);
            unset($data['file']);
            unset($data['uploadfile']);
            unset($data['file1']);
            unset($data['uploadfile1']);
            $addId = Db::name('goods')->insert($data);
            !$addId && $this->error('添加失败');
            $this->redirect('goods/index',['goods_id'=>$data['class']]);
        }else{
            $class_id = input('class_id');
            $class = Db::name('goodsclass')->where('id',$class_id)->find();
            $this->assign('class_id',$class_id);
            $this->assign('class',$class);
            return view();
        }
    }
    public  function save(){
        if(request()->isPost()){
            $data = input('post.');
            $data['img'] = uploadFile("file",140,140);
            $data['poster'] = uploadFile("file1",0,0);
            unset($data['file']);
            unset($data['uploadfile']);
            unset($data['file1']);
            unset($data['uploadfile1']);
            if($data['img'] == null){
                unset($data['img']);
            }
            $saveId = Db::name('goods')->where('id',$data['id'])->update($data);
            !$saveId && $this->error('修改失败');
            $this->redirect('goods/index',['goods_id'=>$data['class']]);
        }else{
            $id = input('id');
            $class_id = input('class_id');
            $data = Db::name('goods')->where('id',$id)->find();
            $class = Db::name('goodsclass')->where('id',$class_id)->find();
            $this->assign('data', $data);
            $this->assign('class', $class);
            $this->assign('class_id', $class_id);
            return view();
        }
    }
    public  function del(){
        $id = input('id');
        $delId=Db::name('goods')->where('id',$id)->delete();
        if ($delId){
            return ajaxmsg('删除成功',1);
        }else{
            return ajaxmsg('删除失败',0);
        }
    }
}