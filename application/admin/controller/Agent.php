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

class Agent extends Controller
{
    public function index(){
        $data = Db::name('agent')->select();
        foreach ($data as $k=>$v){
            $user = Db::name('user')->where('tel',$v['tel'])->find();
            $data[$k]['name'] = $user['name'];
        }
        $this->assign('data',$data);
        return view();
    }
    public function see(){
        $pid = input('pid');
        $data = Db::name('agent')->where('pid',$pid)->select();
        $this->assign('data',$data);
        $this->assign('pid',$pid);
        return view();
    }
    public function lists(){
        $id = input('id');
        $data = Db::name($id .'_detail')->select();
        foreach ($data as $k=>$v){
            if($v['class'] == 1){
                $data[$k]['class'] = '收入';
                if($v['source'] != $id){
                    $data[$k]['source'] = '下级提成';
                }else{
                    $data[$k]['source'] = '直推';
                }
            }else{
                $data[$k]['class'] = '提现';
                if($v['source'] == 1){
                    $data[$k]['source'] = '微信';
                }else{
                    $data[$k]['source'] = '支付宝';
                }
            }
        }
        $this->assign('data',$data);
        $this->assign('id',$id);
        return view();
    }
    public  function save(){
        if(request()->isPost()){
            $data = input('post.');
            $saveId = Db::name('goods')->where('id',$data['id'])->update($data);
            !$saveId && $this->error('修改失败');
            $this->redirect('goods/index',['goods_id'=>$data['goods_id']]);
        }else{
            $id = input('id');
            $data = Db::name('agent')->where('id',$id)->find();
            $parent = '';
            if($data['pid'] != 0){
                $pid = Db::name('agent')->where('id',$data['pid'])->find();
                $parent = $pid['name'];
            }
            $this->assign('data', $data);
            $this->assign('parent', $parent);
            return view();
        }
    }
    public  function del(){
        $id = input('id');
        $delId=Db::name('agent')->where('id',$id)->delete();
        if ($delId){
            return ajaxmsg('删除成功',1);
        }else{
            return ajaxmsg('删除失败',0);
        }
    }
}