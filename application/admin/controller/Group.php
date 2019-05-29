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

class Group extends Controller
{
    public function index(){
        $data = Db::name('group')
//            ->where('id != 1')
            ->select();
//        $data = classsorting($data);.
        $this->assign('data',$data);
        return view();
    }
    public function adds(){
        if(request()->isPost()){
            $data['name'] = input('name');
            $data['time'] = time();
            $link = input('post.');
            $link = $link['link'];
            $addId = Db::name('group')->insertGetId($data);
            !$addId && $this->error('链接添加失败');
            foreach ($link as $v){
                $value['group_id'] = $addId;
                $value['link_id'] = $v['id'];
                $linkId = Db::name('group_link')->insert($value);
                !$linkId && $this->error('链接添加失败');
            }
            $this->success('添加成功','index');
        }else{
            $link = Db::name('link')
//                ->where('id != 1 && pid != 1')
                ->order('sort asc')->select();
            $link = classnav(classsorting($link));
            $this->assign('link', $link);
            return view();
        }
    }
    public  function save(){
        if(request()->isPost()){
            $data = input('post.');
            $link = $data['link'];
            $k = 0;
            unset($data['link']);
//            dump($data);die;
            Db::name('group')->where('id',$data['id'])->update($data);
            $group =  Db::name('group_link')->where('group_id',$data['id'])->column('link_id');
            foreach ($link as $v){
                if (!(in_array($v['id'],$group))){
                    $value['group_id'] = $data['id'];
                    $value['link_id'] = $v['id'];
                    $saveId = Db::name('group_link')->insert($value);
                    !$saveId && $this->error('链接修改失败');
                }
                $linkId[$k] = $v['id'];
                $k++;
            }
            foreach ($group as $v){
                if (!(in_array($v,$linkId))){
//                    dump(1);
                    $saveId = Db::name('group_link')->where('link_id',$v)->delete();
                    !$saveId && $this->error('链接修改失败');
                }
            }
            $this->success('修改成功','index');
        }else{
            $id = input('id');
            $data = Db::name('group')->where('id',$id)->find();
            $class = Db::name('link')
//                ->where('id != 1 && pid !=1')
                ->order('sort asc')->select();
            $link = Db::name('group_link')->where('group_id',$id)->column('link_id');
            foreach ($class as $k => $v) {
                if(in_array($v['id'],$link)){
                    $class[$k]['checked'] = true;
                }else{
                    $class[$k]['checked'] = false ;
                }
            }
            $class = classnav(classsorting($class));
            $this->assign('data', $data);
            $this->assign('link', $class);
            return view();
        }
    }
    public  function del(){
        $id = input('id');
        $delId=Db::name('group')->where('id',$id)->delete();
        Db::name('group_link')->where('group_id',$id)->delete();
        if ($delId){
            return ajaxmsg('删除成功',1);
        }else{
            return ajaxmsg('删除失败',0);
        }
    }
}