<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2018/5/10
 * Time: 15:13
 */

namespace app\admin\controller;

/*
 *系统设置
 */
use think\Controller;
use think\Db;

class System extends Controller
{
    public function index()
    {
        if (request()->isPost()) {
            $data = input('post.');
            $data['img'] = uploadFile("file",0,0);
            $data['share'] = uploadFile("file1",0,0);
            unset($data['file']);
            unset($data['file1']);
            unset($data['uploadfile']);
            unset($data['uploadfile1']);
            if ($data['img'] == null){
                unset($data['img']);
            }
            if ($data['share'] == null){
                unset($data['share']);
            }
            $saveId = Db::name('system')->where('id', $data['id'])->update($data);
            !$saveId && $this->error('修改失败');
            $this->redirect('system/index');
        } else {
            $data = Db::name('system')->find();
            $this->assign('data', $data);
            return view();
        }
    }
}