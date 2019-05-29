<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Session;
/*
 * 首页
 */
class Index extends Controller{
	public function index(){
        $s= Session::get('amanageInfo');
        if (empty($s)){
            $this->redirect('login/index');
        }else{
//            $linkId = Db::
            $group = $s['group_id'];
            $link = Db::name('link')->order('sort asc')->select();
            $data = Db::name('group_link')->where('group_id',$group)->column('link_id');
            foreach ($link as $k => $v) {
                if(!(in_array($v['id'],$data))){
                    unset($link[$k]);
                }
            }
            $link = classnav(systemNav($link));
            $this->assign('link',$link);
            $this->assign('user',$s);
            return view();
        }
	}
	public function doExit(){
		$res = Session::delete('amanageInfo');
		if(!$res){
			$this->success("退出登录成功",'login/index');
		}else{
			$this->error("退出登录失败");
		}
	}
	public function welcome(){
	    return view();
    }
    public function save(){
        if(request()->isPost()){
            $data = input('post.');
            $s = Session::get('amanageInfo');
            $value['password'] = md5($data['pwd']);
            Db::name('admin')->where('id',$s['id'])->update($value);
            return ajaxmsg('修改成功',1);
        }
        return view();
    }

}
?>