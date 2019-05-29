<?php
namespace app\admin\controller;
/*
 * 管理员管理
 */
use think\Controller;
use think\Db;
class Manger extends Controller
{
    public function index()
    {
        $data = Db::name('admin')->where('group_id != 1')->select();
        foreach ($data as $k=>$v){
            $group = Db::name('group')->where('id',$v['group_id'])->find();
//            dump($group);
            $data[$k]['groupname'] = $group['name'];
        }

        $this->assign('data', $data);
//		$this->assign('count',$count);
//		return $this->fetch("manger/mangerlist");
        return view();
    }
    public function adds(){
        if(request()->isPost()){
            $data = input('post.');
//            dump($data);
            unset($data['pwd']);
            $data['password'] = md5($data['password']);
            $data['time'] =time();
            $value = Db::name('admin')->where('username',$data['username'])->find();
            if ($value !=''){
                $this->error('该用户已存在');
            }
            $addId = Db::name('admin')->insert($data);
            !$addId && $this->error('用户添加失败');
            $this->success('添加成功','index');
        }else{
            $group = Db::name('group')->where('id != 1')->select();
            $class = Db::name('major')->where('pid = 0')->select();
//            $list = Db::name('major')->where('pid',$class[0]['id'])->select();
            $this->assign('class', $class);
//            $this->assign('list', $list);
            $this->assign('group', $group);
            return view();
        }
    }
    public  function save(){
        if(request()->isPost()){
            $data = input('post.');
            if($data['password'] == ''){
                unset($data['password']);
            }else{
                $data['password'] = md5($data['password']);
            }
            unset($data['pwd']);
            $saveId = Db::name('admin')->where('id',$data['id'])->update($data);
            !$saveId && $this->error('用户修改失败');
            $this->success('修改成功','index');
        }else{
            $id = input('id');
            $group = Db::name('group')->where('id != 1')->select();
            $user = Db::name('admin')->where('id',$id)->find();
            if($user['m_id'] != 0){
                $data = Db::name('major')->where('id',$user['m_id'])->find();
                $user['pid'] = $data['pid'];
                $list = Db::name('major')->where('pid',$data['pid'])->select();
            }else{
                $user['pid'] = 0;
                $list[0] = [
                    'id'=>0,
                    'name'=>'不限'
                ];
            }
            $class = Db::name('major')->where('pid = 0')->select();
            $this->assign('class', $class);
            $this->assign('list', $list);
            $this->assign('group', $group);
            $this->assign('data', $user);
            return view();
        }
    }
    public function stop(){
        $id  = input('id');
        $data['status'] = 0;
        $saveId = Db::name('admin')->where('id',$id)->update($data);
        if ($saveId){
            return ajaxmsg('已停用',1);
        }else{
            return ajaxmsg('停用失败',0);
        }
    }
    public function start(){
        $id  = input('id');
        $data['status'] = 1;
        $saveId = Db::name('admin')->where('id',$id)->update($data);
        if ($saveId){
            return ajaxmsg('已启用',1);
        }else{
            return ajaxmsg('启用失败',0);
        }
    }
    public  function del(){
        $id = input('id');
        $delId=Db::name('admin')->where('id',$id)->delete();
        if ($delId){
            return ajaxmsg('删除成功',1);
        }else{
            return ajaxmsg('删除失败',0);
        }
    }
    public  function ss(){
        $id = input('id');
        $list = Db::name('major')->where('pid',$id)->select();
//        return ajaxmsg('删除成功',1,$list);
        return $list;
    }
}
?>