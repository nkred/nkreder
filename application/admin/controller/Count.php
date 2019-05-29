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

class Count extends Controller
{
    public function index(){
        $m = Db::name('major')->where('pid = 0')->select();
        $s = [];
        $i = 0;
        foreach ($m as $v){
            $sun = Db::name('major')->where('pid',$v['id'])->select();
            foreach ($sun as $k=>$value){
                $s[$i]['pid'] = $v['name'];
                $s[$i]['m_id'] = $value['name'];
                $num = Db::name('student')->where('m_id',$value['id'])->count();
                $s[$i]['z_num'] = $num;
                $num = Db::name('student')->where('m_id',$value['id'])->where('is_report',1)->count();
                $s[$i]['b_num'] = $num;
                $s[$i]['bdl'] =$s[$i]['b_num']/$s[$i]['z_num']. '%';
                $num = Db::name('student')->where('m_id',$value['id'])->where('is_register',1)->count();
                $s[$i]['c_num'] = $num;
                $s[$i]['zcl'] =$s[$i]['c_num']/$s[$i]['z_num']. '%';
                $i++;
            }
        }
        $num = Db::name('student')->count();
        $data['z_num'] = $num . '人';
        $count = Db::name('student')->where('is_report',1)->count();
        $data['b_num'] = $count . '人';
        $data['bdl'] = $count/$num . '%';
        $count = Db::name('student')->where('is_register',1)->count();
        $data['c_num'] = $count . '人';
        $data['zcl'] = $count/$num . '%';
        $this->assign('data', $data);
        $this->assign('list', $s);
        return view();
    }
}