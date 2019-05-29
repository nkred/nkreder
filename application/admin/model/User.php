<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2018/5/24
 * Time: 15:00
 */

namespace app\admin\model;


class User
{
    public function test(Request $request)
    {
        dump($request->controller());
    }
    public function auth(){
        $auth_ac=test().'/'.ACTION_NAME;
        $username=session('username');
        $auth=array();

        if ($username='123'){
            $id=session('id');
            $info=$this->info('user',array('id'=>$id),'u_role_id');
            $u_role_id=$info['u_role_id'];
            $info=$this->info('role',array('role_id'=>$u_role_id),'role_auth_ids');
            $role_auth_ids=$info['role_auth_ids'];

            $infos=$this->infos('auth',array('auth_id'=>array('in',$role_auth_ids),'auth_level'=>1),'auth_c,auth_a');
            foreach($infos as $k=>$v){
                $auth[]=$v['auth_c'].'/'.$v['auth_a'];
            }
            $res=array_merge($auth,config('AUTH'));
            if (in_array($auth_ac,$res)){
                return true;
            }else{
                return false;
            }

        }else{
            return true;
        }
    }

}