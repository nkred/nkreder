<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2018/5/10
 * Time: 15:13
 */

namespace app\admin\controller;

/*
 * 链接管理
 */
use think\Controller;
use think\Db;

class Link extends Controller
{
    public function index(){
        $data = Db::name('link')->order('sort asc')->select();
        $data = classsorting($data);
        $this->assign('data',$data);
        return view();
    }
    public function adds(){
        if(request()->isPost()){
            $data = input('post.');
            $addId = Db::name('link')->insert($data);
            !$addId && $this->error('链接添加失败');
            $this->success('添加成功','index');
        }else{
            $data = Db::name('link')->order('sort asc')->select();
            $data = classsorting($data);
            $this->assign('data', $data);
            return view();
        }
    }
    public  function save(){
        if(request()->isPost()){
            $data = input('post.');
            $data['time'] = time();
            $saveId = Db::name('link')->update($data);
            !$saveId && $this->error('链接修改失败');
            $this->success('修改成功','index');
        }else{
            $id = input('id');
            $data = Db::name('link')->where('id',$id)->find();
            $class = Db::name('link')->order('sort asc')->select();
            $class = classsorting($class);
            $this->assign('data', $data);
            $this->assign('class', $class);
            return view();
        }
    }
    public  function del(){
        $id = input('id');
//        $id = 26;
        $nextLinkCount = Db('link')->where('pid ='.$id)->count();
//        dump($nextLinkCount);
        if($nextLinkCount){
            return ajaxmsg('链接下还有下级链接，不能删除！',0);
        }
        $delId=Db::name('link')->where('id',$id)->delete();
        if ($delId){
            return ajaxmsg('删除成功',1);
        }else{
            return ajaxmsg('删除失败',0);
        }
    }
    public function Alldel(){
        $id=input('id/a');
        $dataId=implode(',',$id);
        $list=db('link')->where('id',$id)->delete($dataId);
        if ($list){
            $this->success('删除成功','index');
        }else{
            $this->error('删除失败');
        }
    }
}