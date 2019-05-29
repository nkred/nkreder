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

class Distribution extends Controller
{
    public function index(){
        $data = Db::name('dormitory')->where('m_id = 0')->select();
        foreach ($data as $k=>$v){
            $list = Db::name('major')->where('id',$v['m_id'])->find();
            $data[$k]['m_id'] = $list['name'];
            if ($v['m_id'] == 0){
                $data[$k]['m_id'] = '暂未分配专业';
            }
        }
        $this->assign('data',$data);
        return view();
    }
    public  function save(){
        if(request()->isPost()){
            $data = input('post.');
            $saveId = Db::name('dormitory')->where('id',$data['id'])->update($data);
            !$saveId && $this->error('修改失败');
            $this->redirect('index');
        }else{
            $id = input('id');
            $data = Db::name('dormitory')->where('id',$id)->find();
            $class = Db::name('major')->where('pid = 0')->select();
            $list = Db::name('major')->where('pid',$class[0]['id'])->select();
            $this->assign('data', $data);
            $this->assign('class', $class);
            $this->assign('list', $list);
            return view();
        }
    }
    public  function ss(){
        $id = input('id');
        $list = Db::name('major')->where('pid',$id)->select();
//        return ajaxmsg('删除成功',1,$list);
        return $list;
    }
}