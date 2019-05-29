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

class Major extends Controller
{
    public function index(){
        $data = Db::name('major')->where('pid = 0')->select();
        $this->assign('data',$data);
        return view();
    }
    public function major(){
        $id = input('id');
        $data = Db::name('major')->where('pid',$id)->select();
        $this->assign('data',$data);
        $this->assign('pid',$id);
        return view();
    }
    public function adds(){
        if(request()->isPost()){
            $data = input('post.');
            if($data['name'] == ''){
                $this->error('院系名称不能为空');
            }
            $data['time'] = time();
            $addId = Db::name('major')->insert($data);
            !$addId && $this->error('院系添加失败');
            $this->success('添加成功','index');
        }else{
            $data = Db::name('major')->select();
            $data = classsorting($data);
            $this->assign('data', $data);
            return view();
        }
    }
    public function addm(){
        if(request()->isPost()){
            $data = input('post.');
            if($data['name'] == ''){
                $this->error('专业名称不能为空');
            }
            $data['time'] = time();
            $addId = Db::name('major')->insert($data);
            !$addId && $this->error('专业添加失败');
            $this->redirect('major', array('id' => $data['pid']));
        }else{
            $id = input('id');
            $data = Db::name('major')->where('id',$id)->find();
            $this->assign('data', $data);
            return view();
        }
    }
    public  function save(){
        if(request()->isPost()){
            $data = input('post.');
            if($data['name'] == ''){
                $this->error('院系名称不能为空');
            }
            $saveId = Db::name('major')->update($data);
            !$saveId && $this->error('院系修改失败');
            $this->success('修改成功','index');
        }else{
            $id = input('id');
            $data = Db::name('major')->where('id',$id)->find();
            $class = Db::name('major')->select();
            $this->assign('data', $data);
            $this->assign('class', $class);
            return view();
        }
    }
    public  function savem(){
        if(request()->isPost()){
            $data = input('post.');
            if($data['name'] == ''){
                $this->error('专业名称不能为空');
            }
//            $data['time'] = time();
            $saveId = Db::name('major')->update($data);
            !$saveId && $this->error('专业修改失败');
            $this->redirect('major', array('id' => $data['pid']));
        }else{
            $id = input('id');
            $data = Db::name('major')->where('id',$id)->find();
            $class = Db::name('major')->where('id',$data['pid'])->find();
            $this->assign('data', $data);
            $this->assign('class', $class);
            return view();
        }
    }
    public  function del(){
        $id = input('id');
//        $id = 26;
        $nextmajorCount = Db('major')->where('pid ='.$id)->count();
//        dump($nextmajorCount);
        if($nextmajorCount){
            return ajaxmsg('院系下还有下级专业，不能删除！',0);
        }
        $delId=Db::name('major')->where('id',$id)->delete();
        if ($delId){
            return ajaxmsg('删除成功',1);
        }else{
            return ajaxmsg('删除失败',0);
        }
    }
    public function Alldel(){
        $id=input('id/a');
        $dataId=implode(',',$id);
        $list=db('major')->where('id',$id)->delete($dataId);
        if ($list){
            $this->success('删除成功','index');
        }else{
            $this->error('删除失败');
        }
    }
}