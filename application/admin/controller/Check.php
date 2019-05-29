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
use think\Session;

class Check extends Controller
{
    public function index(){
        $data = Db::name('student')->where('is_register',1)->where('is_check',0)->select();
        $this->assign('data',$data);
        return view();
    }
    public  function save(){
        if(request()->isPost()){
            $data = input('post.');
//            dump($data);
            $list['did'] = $data['did'];
            $list['sid'] = $data['id'];
            $saveId = Db::name('d_s')->insert($list);
            !$saveId && $this->error('修改失败');
            $a['is_check'] = 1;
            Db::name('student')->where('id',$data['id'])->update($a);
            $list = Db::name('dormitory')->where('id',$data['did'])->find();
            $list['l_num']++;
            Db::name('dormitory')->where('id',$data['did'])->update($list);
            $this->redirect('index');
        }else{
            $admin = Session::get('amanageInfo');
//            dump($admin);
            $id = input('id');
            $data = Db::name('student')->where('id',$id)->find();
            if($admin['m_id'] == 0){
                $list = Db::name('dormitory')->where('l_num < num')->select();
            }else{
                $list = Db::name('dormitory')->where('l_num < num')->where('m_id',$admin['m_id'])->select();
            }
            $this->assign('data', $data);
            $this->assign('list', $list);
            return view();
        }
    }
}