<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2018/5/10
 * Time: 15:13
 */

namespace app\admin\controller;

/*
 * 注册管理
 */
use think\Controller;
use think\Db;

class Register extends Controller
{
    public function index(){
        $data = Db::name('student')->where('is_report',1)->select();
        foreach ($data as $k=>$v){
            $class = Db::name('major')->where('id',$v['m_id'])->find();
            $pids = Db::name('major')->where('id',$class['pid'])->find();
            $data[$k]['m_id'] = $class['name'];
            $data[$k]['pid'] = $pids['name'];
        }
        $this->assign('data',$data);
        return view();
    }
    /**
     * 注册
    */
    public function start(){
        $id  = input('id');
        $data['is_register'] = 1;
        $saveId = Db::name('student')->where('id',$id)->update($data);
        if ($saveId){
            return ajaxmsg('已报到',1);
        }else{
            return ajaxmsg('报到失败',0);
        }
    }
}