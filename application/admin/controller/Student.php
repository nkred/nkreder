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

class Student extends Controller
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
    public function add(){
        if(request()->isPost()){
            $data = input('post.');
            if ($data['name'] == ''){
                $this->error('姓名不能为空！');
            }
            if ($data['card'] == ''){
                $this->error('考号不能为空！');
            }
            $data['time'] = time();
            $saveId = Db::name('student')->insert($data);
            !$saveId && $this->error('添加失败');
            $this->redirect('index');
        }else{
            $class = Db::name('major')->where('pid = 0')->select();
            $list = Db::name('major')->where('pid',$class[0]['id'])->select();
            $this->assign('class', $class);
            $this->assign('list', $list);
        }
        return view();
    }
    public  function save(){
        if(request()->isPost()){
            $data = input('post.');
            if ($data['name'] == ''){
                $this->error('姓名不能为空！');
            }
            if ($data['card'] == ''){
                $this->error('考号不能为空！');
            }
            $saveId = Db::name('student')->where('id',$data['id'])->update($data);
            !$saveId && $this->error('修改失败');
            $this->redirect('index');
        }else{
            $id = input('id');
            $data = Db::name('student')->where('id',$id)->find();
            $sun = Db::name('major')->where('id',$data['m_id'])->find();
            $data['pid'] = $sun['pid'];
            $class = Db::name('major')->where('pid = 0')->select();
            $list = Db::name('major')->where('pid',$sun['pid'])->select();
            $this->assign('class', $class);
            $this->assign('list', $list);
            $this->assign('data', $data);
            return view();
        }
    }
    public  function del(){
        $id = input('id');
        $delId=Db::name('news')->where('id',$id)->delete();
        if ($delId){
            return ajaxmsg('删除成功',1);
        }else{
            return ajaxmsg('删除失败',0);
        }
    }
    public  function Export(){
        $headArr = ['姓名','考生号','性别','毕业学校','录取分数','年级','院系','专业','学生类别','学制'];
        $data = [
          ['张三','2015364679','男','巫溪职教中心','500','15','管理学院','工商管理','本科','4'],
          ['王五','2015364678','男','巫溪职教中心','501','15','管理学院','工商管理','本科','4'],
        ];
        excelExport('模板',$headArr,$data);
    }
    /**
     * 上传Excel文件
     */
    public function insertExcel(){
        if(request() -> isPost()){
            vendor("PHPExcel.PHPExcel"); //方法一
            $objPHPExcel =new \PHPExcel();
            //获取表单上传文件
            $file = request()->file('excel');
            $info = $file->move(ROOT_PATH . 'public');  //上传验证后缀名,以及上传之后移动的地址  E:\wamp\www\bick\public
            if($info)
            {
//              echo $info->getFilename();
                $exclePath = $info->getSaveName();  //获取文件名
                $file_name = ROOT_PATH . 'public' . DS . $exclePath;//上传文件的地址
                $objReader =\PHPExcel_IOFactory::createReader("Excel5");
                $obj_PHPExcel =$objReader->load($file_name, $encode = 'utf-8');  //加载文件内容,编码utf-8
                $excel_array=$obj_PHPExcel->getSheet(0)->toArray();   //转换为数组格式
                array_shift($excel_array);  //删除第一个数组(标题);
                foreach($excel_array as $k=>$v) {
                    $city['name'] = $v[0];
                    $city['card'] = $v[1];
                    $city['sex'] = $v[2]=='男'?1:0;
                    $city['byxx'] = $v[3];
                    $city['lqfs'] = $v[4];
                    $city['nj'] = $v[5];
                    $city['xslb'] = $v[8];
                    $city['xz'] = $v[9];
                    $city['time'] = time();
                    $class = Db::name('major')->where('name',$v[7])->find();
                    $city['m_id'] = $class['id'];
                    $user = Db::name('student')->where('card',$city['card'])->find();
                    if (!$user){
                        Db::name("student")->insert($city);
                    }
                }

                $this->redirect('index');
            }else
            {
                echo $file->getError();
            }
        }
        $this->redirect('index');
    }
    public  function ss(){
        $id = input('id');
        $list = Db::name('major')->where('pid',$id)->select();
//        return ajaxmsg('删除成功',1,$list);
        return $list;
    }
}