<?php
namespace app\Home\controller;

use think\Controller;

class Index extends Controller
{
    public function index()
    {
        return view();
//        return $this->fetch('../Theme/home/index.html');
    }
    public function data_self_shangjia()
    {
        return $this->fetch('../Theme/home/data_self_shngjia.html');
    }
    public function search()
    {
        return $this->fetch('../Theme/homea/search.html');

    }
}
