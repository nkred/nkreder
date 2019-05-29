<?php
function ajaxmsg($msg ='',$status = 1,$data = '',$errcode = '')
{
    $json['msg'] = $msg;
    $json['data'] = $data;
    $json['status'] = $status;
    if ($errcode) {
        $json['errcode'] = $errcode;
    }
    return json_encode($json, true);
}