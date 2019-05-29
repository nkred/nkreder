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

class Find extends Controller
{
    public function index(){
        $data = Db::name('student')->select();
        foreach ($data as $k=>$v){
            $class = Db::name('major')->where('id',$v['m_id'])->find();
            $pids = Db::name('major')->where('id',$class['pid'])->find();
            $data[$k]['m_id'] = $class['name'];
            $data[$k]['pid'] = $pids['name'];
            $did = Db::name('d_s')->where('sid',$v['id'])->find();
            $d = Db::name('dormitory')->where('id',$did['did'])->find();
            $data[$k]['did'] = $d['name'];
        }
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