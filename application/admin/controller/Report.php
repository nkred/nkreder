<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2018/5/10
 * Time: 15:13
 */

namespace app\admin\controller;

/*
 * 报到管理
 */
use think\Controller;
use think\Db;

class Report extends Controller
{
    public function index(){
        $data = Db::name('student')->select();
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
     * 报到
    */
    public function start(){
        $id  = input('id');
        $data['is_report'] = 1;
        $saveId = Db::name('student')->where('id',$id)->update($data);
        if ($saveId){
            return ajaxmsg('已启用',1);
        }else{
            return ajaxmsg('启用失败',0);
        }
    }
}