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

class Ranking extends Controller
{
    public function index(){
        $m = date('m')-1;
        $time = strtotime(date('Y-'.$m.'-01'));
        $end = strtotime(date('Y-m-01'));
        $ranking = Db::name('ranking')->where('time',$time)->count();
        if($ranking == 0) {
            Db::name('ranking')->delete(true);
            $id = Db::name('agent')->column('id');
            $k = 0;
            $where = 'time >= ' . $time .' and time <= ' .$end;
            foreach ($id as $v) {
                $detail = Db::name($v . '_detail')->where('class', 1)->where($where)->sum('num');
                $data[$k]['user'] = $v;
                $data[$k]['num'] = $detail;
                $k++;
            }
            for($i = 0; $i < count($data); $i++){
                for($j = $i+1; $j < count($data); $j++){
                    if ($data[$i]['num'] < $data[$j]['num']) {
                        $key = $data[$i];
                        $data[$i] = $v;
                        $data[$j] = $key;
                    }
                }
            }
            foreach ($data as $k => $v) {
                $value = $v;
                $value['id'] = $k + 1;
                $value['stauts'] = 1;
                if ($k < 10) {
                    $value['stauts'] = 0;
                }
                $value['time'] = $time;
                Db::name('ranking')->insert($value);
            }
        }
        $data = Db::name('ranking')->select();
        foreach ($data as $k=>$v){
            $agent = Db::name('agent')->where('id',$v['user'])->find();
            $data[$k]['name'] = $agent['name'];
        }
        $this->assign('data', $data);
        return view();
    }
    public  function see(){
        if(request()->isPost()){
            $data = input('post.');
            $agent = Db::name('agent')->where('id',$data['id'])->find();
            $agent['money'] +=$data['num'];
            Db::name('agent')->where('id',$data['id'])->update($agent);
            $value['stauts'] = 1;
            Db::name('ranking')->where('user',$data['id'])->update($value);
            return ajaxmsg('奖励成功',1);
        }else{
            $id = input('id');
            $this->assign('id', $id);
            return view();
        }
    }
    public  function del(){
        $id = input('id');
        $delId=Db::name('ranking')->where('id',$id)->delete();
        if ($delId){
            return ajaxmsg('删除成功',1);
        }else{
            return ajaxmsg('删除失败',0);
        }
    }
}