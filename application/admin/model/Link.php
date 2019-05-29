<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2018/5/24
 * Time: 15:00
 */

namespace app\admin\model;


use think\Model;

class Link
{
    protected $_link = array(
        'group' => array(
            'mapping_type' => self::MANY_TO_MANY,
            'forgin_key' => 'link_id',
            'relation_foreign_key'  =>  'group_id',
            'relation_table'    =>  'k_group_link',
            'mapping_fields' => 'title',
        )
    );

}