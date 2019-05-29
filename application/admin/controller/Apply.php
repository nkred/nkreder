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

class Apply extends Controller
{
    public function index(){
        $data = Db::name('order')->select();
        foreach ($data as $k=>$v){
            $goods = Db::name('goods')->where('id',$v['goods'])->find();
            $data[$k]['goods'] = $goods['name'];
        }
        $this->assign('data',$data);
        return view();
    }
    public  function see(){
        $id = input('id');
        $data = Db::name('order')->where('user',$id)->select();
        foreach ($data as $k=>$v){
            $goods = Db::name('goods')->where('id',$v['goods'])->find();
            $data[$k]['goods'] = $goods['name'];
        }
//        dump($data);
        $this->assign('data', $data);
        $this->assign('id', $id);
        return view();
    }
    public function stop(){
        $id  = input('id');
        $data['status'] = 2;
        $saveId = Db::name('order')->where('id',$id)->update($data);
        if ($saveId){
            return ajaxmsg('已修改',1);
        }else{
            return ajaxmsg('修改失败',0);
        }
    }
    public function start(){
        $id  = input('id');
        $data['status'] = 1;
        $saveId = Db::name('order')->where('id',$id)->update($data);
        $system = Db::name('system')->find();
        if ($saveId){
            $order = Db::name('order')->where('id',$id)->find();
            $goods = Db::name('goods')->where('id',$order['goods'])->find();
            $value['class'] = 1;
            $value['source'] = $order['agent'];
            $value['goods_id'] = $goods['id'];
            $value['num'] = $goods['bonus'];
            $value['time'] = time();
            Db::name($order['agent'].'_detail')->insert($value);
            $agent = Db::name('agent')->where('id',$order['agent'])->find();
            $agent ['money'] += $value['num'];
            if($agent['pid'] != 0){
                $parent =  Db::name('agent')->where('id',$agent['pid'])->find();
                $parent['money'] += $value['num'] * ($system['pdistribution']/100);
                $value['class'] = 3;
                if($parent['pid'] != 0){
                    $parents =  Db::name('agent')->where('id',$parent['pid'])->find();
                    $parents['money'] += $value['num'] * ($system['tdistribution']/100);
                    $value['num'] = $value['num'] * ($system['tdistribution']/100);
                    $value['class'] = 3;
                    Db::name($parents['id'].'_detail')->insert($value);
                    Db::name('agent')->where('id',$parents['id'])->update($parents);
                }
                $value['num'] = $value['num'] * ($system['pdistribution']/100);
                Db::name($parent['id'].'_detail')->insert($value);
                Db::name('agent')->where('id',$parent['id'])->update($parent);
            }
            Db::name('agent')->where('id',$agent['id'])->update($agent);
            return ajaxmsg('已修改',1);
        }else{
            return ajaxmsg('修改失败',0);
        }
    }
    public  function del(){
        $id = input('id');
        $delId=Db::name('user')->where('id',$id)->delete();
        if ($delId){
            return ajaxmsg('删除成功',1);
        }else{
            return ajaxmsg('删除失败',0);
        }
    }
}