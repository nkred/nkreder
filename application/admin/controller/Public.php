<?php
namespace app\admin\controller;
/*
 * 公共文件
 */
use think\Controller;
use think\Db;
class Public extends Controller{
	//头部文件
	public function header(){
		return $this->fetch('Public/header');
	}
    //底部文件
	public function footer(){
		return $this->fetch('Public/footer');
	}
}
?>