<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2018/5/24
 * Time: 15:00
 */

namespace app\admin\model;


use think\model\Relation;

class Group extends Relation
{
    protected $_link  = array(
        'link' => array(
            'mapping_type' => self::MANY_TO_MANY,
            'forgin_key' => 'group_id',
            'relation_foreign_key'  =>  'link_id',
            'relation_table'    =>  's_group_link',
        ),
        'manage' => array(
            'mapping_type' => self::HAS_MANY,
            'forgin_key' => 'group_id',
            //'mapping_name' => 'username', //显示的下标名
            //'mapping_fields'=>'id', //需要显示的字段
            //'as_fields'=>'title:username', //直接把关联的字段提升到数组中
        ),
    );

}