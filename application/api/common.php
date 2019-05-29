<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------
//电商ID
use think\Db;
//require_once (__DIR__.'/alipay/AopSdk.php');
function build_order_no(){
    return date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
}
// 微信公众号支付  $order 订单号 $name 支付名 $price 支付金额 $goodsname 商品名
function WxPay($order,$name,$price,$goodsname,$openid){
    $wxpay=PAY_PATH.'wxpay/lib/WxPay.Api.php';//引入
    include($wxpay);
    $wpay =PAY_PATH.'wxpay/WxPay.JsApiPay.php';//引入
    include($wpay);
    $tools = new \JsApiPay();
    // $openId = $tools->GetOpenid();
    //②、统一下单
    $input = new \WxPayUnifiedOrder();
    $input->SetBody($name);
    $input->SetAttach($name);
    $input->SetOut_trade_no($order);//订单号
    $input->SetTotal_fee($price);//支付金额
    $input->SetTime_start(date("YmdHis"));//时间
    $input->SetTime_expire(date("YmdHis", time() + 600));
    $input->SetGoods_tag($goodsname);//商品名
    $input->SetNotify_url("http://gene.maker66.cn/api/index/wxNotify");//回调地址
    $input->SetTrade_type("JSAPI");
    $input->SetOpenid($openid);
    $config = new \WxPayConfig();
    $order = \WxPayApi::unifiedOrder($config, $input);
    $stringA = "appId=".$order['appid']."&nonceStr=".$order['nonce_str']."&package=prepay_id=".$order['prepay_id']."&signType=MD5&timeStamp=".$order['mch_id']."";
    $stringSignTemp = $stringA . "&key=Celemejiyinjiance201820182018201";
    $order['paysign'] = MD5($stringSignTemp);
    return $order;
    $jsApiParameters = $tools->GetJsApiParameters($order);
    //return $jsApiParameters;
    // //获取共享收货地址js函数参数
    $editAddress = $tools->GetEditAddressParameters();
    return $editAddress;
}
//数组转XML
function ArrToXml($arr){
    if(!is_array($arr) || count($arr) == 0) return '';
    $xml = "<xml>";
    foreach ($arr as $key=>$val){
        if (is_numeric($val)){
            $xml.="<".$key.">".$val."</".$key.">";
        }else{
            $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
        }
        }
    $xml.="</xml>";
    return $xml;
}
//发送数据
function postData($url,$postfields){
    $ch = curl_init();
    $params[CURLOPT_URL] = $url;    //请求url地址
    $params[CURLOPT_HEADER] = false; //是否返回响应头信息
    $params[CURLOPT_RETURNTRANSFER] = true; //是否将结果返回
    $params[CURLOPT_FOLLOWLOCATION] = true; //是否重定向
    $params[CURLOPT_POST] = true;
    $params[CURLOPT_POSTFIELDS] = $postfields;
    $params[CURLOPT_SSL_VERIFYPEER] = false;
    $params[CURLOPT_SSL_VERIFYHOST] = false;
    //以下是证书相关代码
    $params[CURLOPT_SSLCERTTYPE] = 'PEM';
    $params[CURLOPT_SSLCERT] = getcwd().'/plugins/payment/weixin/cert/apiclient_cert.pem';//绝对路径
    $params[CURLOPT_SSLKEYTYPE] = 'PEM';
    $params[CURLOPT_SSLKEY] = getcwd().'/plugins/payment/weixin/cert/apiclient_key.pem';//绝对路径
    curl_setopt_array($ch, $params); //传入curl参数
    $content = curl_exec($ch); //执行
    curl_close($ch); //关闭连接
    return $content;
}
function transfer($data){
    //支付信息
    $wxpay=PAY_PATH.'wxpay/lib/WxPay.Api.php';//引入
    include($wxpay);
    $wpay =PAY_PATH.'wxpay/WxPay.JsApiPay.php';//引入
    include($wpay);
    $wpay =PAY_PATH.'wxpay/WxPay.Config.php';//引入
    include($wpay);
    $wxchat['appid'] = \WxPayConfig::$appid;
    $wxchat['mchid'] = \WxPayConfig::$mchid;
    $webdata = array(
        'mch_appid' => $wxchat['appid'],//商户账号appid
        'mchid'     => $wxchat['mchid'],//商户号
        'nonce_str' => md5(time()),//随机字符串
        'partner_trade_no'=> $data['order'], //商户订单号，需要唯一
        'openid' => $data['openid'],//转账用户的openid
        'check_name'=> 'NO_CHECK', //OPTION_CHECK不强制校验真实姓名, FORCE_CHECK：强制 NO_CHECK：
        'amount' => $data['money'], //付款金额单位为分
        'desc'   => '微信企业付款到零钱',//企业付款描述信息
        'spbill_create_ip' => request()->ip(),//获取IP
    );
    foreach ($webdata as $k => $v) {
        $tarr[] =$k.'='.$v;
    }
    sort($tarr);
    $sign = implode($tarr, '&');
    $sign .= '&key='.\WxPayConfig::$key;
    $webdata['sign']=strtoupper(md5($sign));
    $wget = $this->ArrToXml($webdata);//数组转XML
    $pay_url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';//api地址
    $res = $this->postData($pay_url,$wget);//发送数据
    if(!$res){
        return array('status'=>1, 'msg'=>"Can't connect the server" );
    }
    $content = simplexml_load_string($res, 'SimpleXMLElement', LIBXML_NOCDATA);
    if(strval($content->return_code) == 'FAIL'){
        return array('status'=>1, 'msg'=>strval($content->return_msg));
    }
    if(strval($content->result_code) == 'FAIL'){
        return array('status'=>1, 'msg'=>strval($content->err_code),':'.strval($content->err_code_des));
    }
    $rdata = array(
        'mch_appid'        => strval($content->mch_appid),
        'mchid'            => strval($content->mchid),
        'device_info'      => strval($content->device_info),
        'nonce_str'        => strval($content->nonce_str),
        'result_code'      => strval($content->result_code),
        'partner_trade_no' => strval($content->partner_trade_no),
        'payment_no'       => strval($content->payment_no),
        'payment_time'     => strval($content->payment_time),
        'status'=>0,
    );
    return $rdata;
}

function upload($file)
{
    $upload = request()->file($file);
    if ($upload) {
        if(is_array($upload)){
            foreach ($upload as $k=>$v){
                $info[] = $v->validate(['size' => 808080, 'ext' => 'jpg,gif,png,jpeg,zip,gz,rar,doc,docx,xls,xlsx,ppt,pptx,txt,swf'])->move(ROOT_PATH . 'public' . DS . 'uploads');
            }
        }else{
            $info = $upload->validate(['size' => 808080, 'ext' => 'jpg,gif,png,jpeg,zip,gz,rar,doc,docx,xls,xlsx,ppt,pptx,txt,swf'])->move(ROOT_PATH . 'public' . DS . 'uploads');
        }
        if ($info) {
            if (is_array($info)){
                foreach ($info as $i){
                    $item[] = $i->GetSaveName();
                    $image=json_encode($item);
                }
            }else{
                $image = $info->GetSaveName();
            }
            return $image;
        } else {
            return $upload->getError();
        }
    }
}
/*
 * 字符串截取
 */
function subtext($text,$length){
    if(mb_strlen($text, 'utf8') > $length)
        return mb_substr($text,0,$length,'utf8');
        return $text;
    }
/**
 * 以json方式格式化输出
 *
 * @param int $status 状态：0-失败，1-成功
 * @param int $msg 返回状态码
 * @param string/array $data 数据
 * @param string $text 提示文字
 * @return string
 */
function jsonReturn($status=0, $code=0, $data='', $msg='',$type = 1)
{

    $json_arr = array('status'=>$status,'code'=>$code);
    if(!empty($msg)){
        $json_arr['msg'] = $msg;
    }
    if(!empty($data)){
        $json_arr['data'] = $data;
    }
    header('Content-Type:application/json; charset=utf-8');
    if ($type == 1) {
        exit(json_encode($json_arr));
    } else if ($type == 2) {
        return json_encode($json_arr);
    }

}
function createCode($userId)
{
    $code = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $rand = $code[rand(0,25)]
        .strtoupper(dechex(date('m')))
        .date('d')
        .substr(time(),-5)
        .substr(microtime(),2,5)
        .sprintf('%02d',rand(0,99));
    for(
        $a = md5( $rand, true ),
        $s = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ',
        $d = '',
        $f = 0;
        $f < 6;
        $g = ord( $a[ $f ] ),
        $d .= $s[ ( $g ^ ord( $a[ $f + 8 ] ) ) - $g & 0x1F ],
        $f++
    );
    return $d;
}
function uploadFile($name,$width,$height){
    $file = request()->file($name);
    if($file){
        $filePaths = 'uploads';
        if(!file_exists($filePaths)){
            mkdir($filePaths,0777,true);
        }
        $info = $file->move($filePaths);
        if($info){
            $imgpath = 'uploads/'.'/'.$info->getSaveName();
            $image = \think\Image::open($imgpath);
            $date_path = 'uploads/'.date('Ymd');
            if(!file_exists($date_path)){
                mkdir($date_path,0777,true);
            }
            $thumb_path = $date_path.'/'.$info->getFilename();
            $image->thumb($width, $height)->save($thumb_path);
            $data['img'] = $imgpath;
            $data['thumb_img'] = $thumb_path;
            return $data['thumb_img'];
        }else{
            // 上传失败获取错误信息
            return $file->getError();
        }
    }
}
function uploadFiles ($name,$width,$height){
    $files = request()->file($name);
    if ($files) {
        $filePaths = 'uploads';
        if (!file_exists($filePaths)){
            mkdir($filePaths, 0777, true);
        }
        $res =[];
        $error =[];
        foreach ($files as $k=> $file) {
            $info = $file->move($filePaths);
            if ($info) {
                $imgpath = 'uploads/' . '/' . $info->getSaveName();
                $image = \think\Image::open($imgpath);
                $date_path = 'uploads/' . date('Ymd');
                if (!file_exists($date_path)) {
                    mkdir($date_path, 0777, true);
                }
                $thumb_path = $date_path . '/' . $info->getFilename();
                $image->thumb($width, $height)->save($thumb_path);
                $data[$k]['img'] = $imgpath;
                $data[$k]['thumb_img'] = $thumb_path;
                $res = $data;
            } else {
                return $file->getError();
            }
        }
        return $res;
  }
}
//获取OpenId
function Wx_GetOpenidByCode($code){
    $appid ="wx1f35845d8c83a024";
    $secret = "df237f8b3bd4c94f7fd5c859eaa598ff";
    $url = "https://api.weixin.qq.com/sns/jscode2session?appid=$appid&secret=$secret&js_code=$code&grant_type=authorization_code";
    //通过code换取网页授权access_token
    $weixin =  file_get_contents($url);
    $jsondecode = json_decode($weixin); //对JSON格式的字符串进行编码
    $array = get_object_vars($jsondecode);//转换成数组
//    return $array;
    $openid = $array['openid'];//输出openid
    return $openid;
}
function new_table($id){
    $sql = "CREATE TABLE `l_".$id."_detail` (
      `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `class` tinyint(1) unsigned NOT NULL COMMENT '类型(0提出1直推2分润3提成)',
      `source` int(11) unsigned DEFAULT NULL COMMENT '来源(直推:产品id,支出:0支付宝1微信,分润+提成:下线id)',
      `goods_id` int(11) unsigned DEFAULT NULL COMMENT '产品id',
      `num` decimal(20,2) unsigned NOT NULL COMMENT '金额',
      `time` int(11) unsigned NOT NULL COMMENT '时间',
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
    ";
    Db::execute($sql);
    }function delDirAndFile($path, $delDir = FALSE) {
    if (is_array($path)) {
        foreach ($path as $subPath)
            delDirAndFile($subPath, $delDir);
    }
    if (is_dir($path)) {
        $handle = opendir($path);
        if ($handle) {
            while (false !== ( $item = readdir($handle) )) {
                if ($item != "." && $item != "..")
                    is_dir("$path/$item") ? delDirAndFile("$path/$item", $delDir) : unlink("$path/$item");
            }
            closedir($handle);
            if ($delDir)
                return rmdir($path);
        }
    } else {
        if (file_exists($path)) {
            return unlink($path);
        } else {
            return FALSE;
        }
    }
    clearstatcache();
}