<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2018/6/1
 * Time: 14:24
 */

namespace app\Api\controller;
//require_once (__DIR__.'/alipay/AopSdk.php');
header('Access-Control-Allow-Origin:*');
header("Content-type: text/html; charset=utf-8");
//header('Access-Allow-Credentials:true');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, POST, PUT');
use think\Controller;
use think\Cookie;
use think\Db;
use think\File;
use think\Request;
use think\Loader;
Loader::import('wapalipay.wappay.service.AlipayTradeService',EXTEND_PATH,'.php');
Loader::import('wapalipay.wappay.buildermodel.AlipayTradeWapPayContentBuilder',EXTEND_PATH,'.php');
Loader::import('wapalipay.wappay.aop.AopClient',EXTEND_PATH,'.php');
Loader::import('wapalipay.wappay.aop.request.AlipayFundTransToaccountTransferRequest',EXTEND_PATH,'.php');
Loader::import('wapalipay.wappay.aop.request.AlipayTradeQueryRequest',EXTEND_PATH,'.php');
Loader::import('wapalipay.wappay.aop.SignData',EXTEND_PATH,'.php');
class Index extends Controller
{
   public function setUser(){
       $data['tel'] = input('tel');
       $data['pwd'] = md5(input('pwd'));
       $data['pid'] = input('pid');
       $user = Db::name('user')->where('tel',$data['tel'])->find();
       if($user != null){
           $addId = 2;
       }else{
           $value = Db::name('user')->order('id desc')->find();
           $num = $value['id']+1;
           $data['name'] = '用户U_' . $num;
           $data['time'] = time();
           $data['sex'] = 1;
           $data['img'] = 'uploads/20180910/0ac33b0ac9df54126f221a1c6c02db6d.jpg';
           $addId = Db::name('user')->insert($data);
       }
       return json_encode($addId);
   }
    /**
     * @User 一秋
     * @param $userid  用户id
     * @param $out_biz_no 编号
     * @param $payee_account 提现的支付宝账号
     * @param $amount 转账金额
     * @param $payee_real_name 账号的真实姓名
     * @return bool|Exception
     */
    //$userid,$out_biz_no,$payee_account,$amount,$payee_real_name
    public function userWithDraw(){
        $out_biz_no = time()+rand(10,99);
        $id = input('id');
        $payee_account=input('payee_account');
        $amount=input('amount');
        $payee_real_name = input('name');
        $payer_show_name = '用户提现';
        $remark = '提现到支付宝';
        $aop = new \AopClient();
        $aop->gatewayUrl =  "https://openapi.alipay.com/gateway.do";//支付宝网关 https://openapi.alipay.com/gateway.do这个是不变的
        $aop->appId = "2018091061358020";//商户appid 在支付宝控制台找
        $aop->rsaPrivateKey = "MIIEowIBAAKCAQEAsrG0C+ks6X3DgaEnj9CgQFy+0yDbn6OIVkbhxe4BtLDBgyRZOo2RVU3SBE9vw0V/+GWs1fWOhtdb6HdQjFMYJlVHAQMJWCcgUoZ68P1jgws4eD1nKkss8MbQBjAtWIMq0Tr2Ud5HvHY0guTy2FRKPEwtWgM0hp7W1i4mOwV2kpUrZfJeU1YexmTlWOgkvxkPw6SqPK7IMFtdKhq4z790pnwIwQhf7seUmyn/p1xPs7QEPLV42oU3IC6nBRIHUM2SrpBEYM5w+BZ2tQOqVjnU3O+JAMQg/zcfmz2M7w7+AC+GPhxzvRYRpUVjtTRzeRL88elTpN1plP7u3Li3zutg0wIDAQABAoIBAG8Z85KwQF9P9T1koD9XfDtoLrEALezcdUkHOxqf92mLuFU8RUoVePXrs5dAeIGVdZJQTRyeVyZZQeiQPoFwrIQw4zuq0qV/diY5PLkXIkb4s/x2WO0/3ko5ol8mlxsAOp/qI/oU1M9FQzLa/G4qQhmpV00uzvqgSXzTJqvtoqJRl8ujwBTpdLxyf0YA6k+h+UMflSwz3HS1IDSE6ZzJYkQE+oxQ6vmEQOJ1FrWIniTx2HwKSbEqkVLzBNs+L/xPqvW3Tmdi4lT25cLwNnzELQHlrw6jsd95QuK1SlYeLnMzCS/SeXcqfxmGU+tISk9n+nppR7ZClg9Ox5xh14BlztECgYEA6JDf3kq5I7GYpBFhy5ogGLfzareXiZtPOr63AlQzOeiwwswnJdDz879H94QI1obuzbp5egmNMWHHFi7SjGPqYAskEoALP9XnbamnNHU8MNMyJURxKoekIytV4aqbK/6T23qoLibIXsxHwkCqPuzKwST3QT/97GZKwompvKt2xNkCgYEAxLMvt1+f9GdDQ1sWfVRERRAyscWILXiO4JxZgK4BAVCzQETWH6uGTHjOLXDKEsbAnhq6kVgbgpoyA3TQ51rWHzKHE9+2IcxpSKBEVRPBwxYoA1nlIDoY7iz34MY69WaEbM3/pqztelQdqC4dt/oiArOCMg/EHQbdy92WrMRXF4sCgYBx6SP92VO9a+t70Re5hhBaix8rEimjOPMhrvAsr9k3cAXEJAK4vxP+O56gWYeKrCSjl8aQQ8Vofp/o+Z4fLYFK8aoFdboM1lS4nfRL+XA16fwRzOgxEKcQotU55zqB3fvF8NoXYN+brysmtk2s7IaS++wvhYx2EdRkXNFKnCFcYQKBgEpU5I1JZ6r2cua7P05xT7NBocaoPQPh8SxUKUaFev/CPVrmKHyjVwcDB/cIDnT2Asg8lZBjWVILbyb7OA6VtyDm+UC7Y5p2Irs9RVVZN/m/H7PUfs+k5NLsz2dzwcgGy7hKSHf2FXAK5YIumzBxJ/UZXPQKHTRS6fImdNApCjhtAoGBAIozUA5jLaXS3xNZ+FiYUl/3Pds+Y5DrLSDXuQ6qZlXGJniILY+yASyZnSgrgQnYuW6EMzuKSs94fSQlNGLR0WcQZyvbIsphKEZW4Es4b1LxBHgl2R0ht9QYT9lccQGnFq3d7KD+bmXVpbPXlsi+4qNSuZYCMEPOiYKWCv6+yA7/";//私钥 工具生成的
        $aop->alipayrsaPublicKey="MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAjKH8QespGqD/A8h27wWHKMrDvHkoBU8deRAS4Z0URQDjyZ4Wj/OPL6usxvheJlQgx9xT8VkbzgMLke9HnssabteLcpR3dD2fkb0Aqc7gQX7eRFhFdSFsjO0Gx7XVwBXCPP4yAvtvgTN1bVAH7UN0tya7mPWvweLRUIR7xsZnInv4joRnXNOEzMlr91zBKQlthtlxlLLjGyebomg5Ko4uZhTc2xmnGOM7CJrJAFyVTl0PLH9OuVWGwboy450FgqIz4XG1Qk9kvQNM6TJ5tZYQnr0XpmZH66pkDUB8CZ51i45I+OKjM0uN1g00fydwr2OehYuBLD2+cFs7jvTyTzIVsQIDAQAB";//支付宝公钥 上传应用公钥后 支付宝生成的支付宝公钥
        $aop->apiVersion = '1.0';
        $aop->signType = 'RSA2';
        $aop->postCharset='utf-8';
        $aop->format='json';
        $request = new \AlipayFundTransToaccountTransferRequest();
        $request->setBizContent("{" .
            "\"out_biz_no\":\"$out_biz_no\"," .
            "\"payee_type\":\"ALIPAY_LOGONID\"," .
            "\"payee_account\":\"$payee_account\"," .
            "\"amount\":\"$amount\"," .
            "\"payer_show_name\":\"$payer_show_name\"," .
            "\"payee_real_name\":\"$payee_real_name\"," .
            "\"remark\":\"$remark\"" .
            "}");
        $result = $aop->execute($request);
        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $resultCode = $result->$responseNode->code;
        if(!empty($resultCode)&&$resultCode == 10000){
            $data['class'] = 0;
            $data['source'] = 0;
            $data['num'] = $amount;
            $data['time'] = time();
            Db::name($id.'_detail')->insert($data);
            $agent = Db::name('check')->where('id',$id)->find();
            $pid = Db::name('check')->where('id',$agent['pid'])->find();
            if($pid['level'] == '团长'){
                $system = Db::name('system')->find();
                $value['class'] = 2;
                $value['source'] = $id;
                $value['num'] = $amount*($system['maid']/100);
                $value['time'] = time();
                Db::name($pid['id'].'_detail')->insert($value);
            }
        }
        return $result->$responseNode->sub_msg;
    }
    public function wxPay(){
        $code = input('code');
        $openid = Wx_GetOpenidByCode($code);
        $order  = build_order_no();//订单号
        cache('order',$order);
        $result = WxPay($order,'支付',1,'成为代理人',$openid);
        $id = input('id');
        $value = Db::name('user')->where('id',$id)->find();
        $agent = Db::name('check')->where('tel',$value['tel'])->find();
        if($agent==''){
            $data['name'] = input('name');
            $data['card'] = input('card');
            $data['level'] = '会员';
            $data['tel'] = $value['tel'];
            $data['code'] = $order;
            $data['pid'] = $value['pid'];
            $data['money'] = 0;
            $data['time'] = time();
            Db::name('check')->insert($data);
        }else{
            $data['code'] = $order;
            $data['time'] = time();
            Db::name('check')->where('id',$agent['id'])->update($data);
        }
        $count =  Db::name('check')->where('pid',$data['pid'])->count();
        if($count >= 50){
            $pid = Db::name('check')->where('id',$data['pid'])->find();
            if($pid['level'] != '团长'){
                $pid['level'] = '团长';
                Db::name('check')->where('id',$pid['id'])->update($pid);
            }
        }
        return json($result);
    }
    public function wxWithDraw(){
        $code = input('code');
        $data['openid'] = Wx_GetOpenidByCode($code);
        $data['order']  = build_order_no();//订单号
        $data['money'] = input('money');
        $result = transfer($data);
        if($result['status'] == 0){
            $id = input('id');
            $datas['class'] = 0;
            $datas['source'] = 1;
            $datas['num'] = $data['money'];
            $datas['time'] = time();
            Db::name($id.'_detail')->insert($datas);
            $agent = Db::name('check')->where('id',$id)->find();
            $pid = Db::name('check')->where('id',$agent['pid'])->find();
            if($pid['level'] == '团长'){
                $system = Db::name('system')->find();
                $value['class'] = 2;
                $value['source'] = $id;
                $value['num'] = $data['money']*($system['maid']/100);
                $value['time'] = time();
                Db::name($pid['id'].'_detail')->insert($value);
            }
        }
        return json($result);
    }
    // 微信公众号回调
    public function wxNotify(){
        $order = cache('order');
        $data = Db::name('check')->where('code',$order)->find();
        $data['code'] = 0;
        $res = Db::name('check')->where('code',$order)->update($data);
        new_table($data['id']);
        if($res){
            return json_encode(['status'=>1]);
        }else{
            return json_encode(['status'=>0]);
        }
    }
   //修改密码
   public function setPwd(){
       $data['id'] = input('id');
       $data['pwd'] = md5(input('pwd'));
       $saveId = Db::name('user')->where('id',$data['id'])->update($data);
       return json_encode($saveId);
   }
    public function pay(){
        $config = array (
            //应用ID,您的APPID。
            'app_id' => "2018091061358020",
//            'app_id' => "2016091700531363",
            //商户私钥，您的原始格式RSA私钥
            'merchant_private_key' => "MIIEowIBAAKCAQEAsrG0C+ks6X3DgaEnj9CgQFy+0yDbn6OIVkbhxe4BtLDBgyRZOo2RVU3SBE9vw0V/+GWs1fWOhtdb6HdQjFMYJlVHAQMJWCcgUoZ68P1jgws4eD1nKkss8MbQBjAtWIMq0Tr2Ud5HvHY0guTy2FRKPEwtWgM0hp7W1i4mOwV2kpUrZfJeU1YexmTlWOgkvxkPw6SqPK7IMFtdKhq4z790pnwIwQhf7seUmyn/p1xPs7QEPLV42oU3IC6nBRIHUM2SrpBEYM5w+BZ2tQOqVjnU3O+JAMQg/zcfmz2M7w7+AC+GPhxzvRYRpUVjtTRzeRL88elTpN1plP7u3Li3zutg0wIDAQABAoIBAG8Z85KwQF9P9T1koD9XfDtoLrEALezcdUkHOxqf92mLuFU8RUoVePXrs5dAeIGVdZJQTRyeVyZZQeiQPoFwrIQw4zuq0qV/diY5PLkXIkb4s/x2WO0/3ko5ol8mlxsAOp/qI/oU1M9FQzLa/G4qQhmpV00uzvqgSXzTJqvtoqJRl8ujwBTpdLxyf0YA6k+h+UMflSwz3HS1IDSE6ZzJYkQE+oxQ6vmEQOJ1FrWIniTx2HwKSbEqkVLzBNs+L/xPqvW3Tmdi4lT25cLwNnzELQHlrw6jsd95QuK1SlYeLnMzCS/SeXcqfxmGU+tISk9n+nppR7ZClg9Ox5xh14BlztECgYEA6JDf3kq5I7GYpBFhy5ogGLfzareXiZtPOr63AlQzOeiwwswnJdDz879H94QI1obuzbp5egmNMWHHFi7SjGPqYAskEoALP9XnbamnNHU8MNMyJURxKoekIytV4aqbK/6T23qoLibIXsxHwkCqPuzKwST3QT/97GZKwompvKt2xNkCgYEAxLMvt1+f9GdDQ1sWfVRERRAyscWILXiO4JxZgK4BAVCzQETWH6uGTHjOLXDKEsbAnhq6kVgbgpoyA3TQ51rWHzKHE9+2IcxpSKBEVRPBwxYoA1nlIDoY7iz34MY69WaEbM3/pqztelQdqC4dt/oiArOCMg/EHQbdy92WrMRXF4sCgYBx6SP92VO9a+t70Re5hhBaix8rEimjOPMhrvAsr9k3cAXEJAK4vxP+O56gWYeKrCSjl8aQQ8Vofp/o+Z4fLYFK8aoFdboM1lS4nfRL+XA16fwRzOgxEKcQotU55zqB3fvF8NoXYN+brysmtk2s7IaS++wvhYx2EdRkXNFKnCFcYQKBgEpU5I1JZ6r2cua7P05xT7NBocaoPQPh8SxUKUaFev/CPVrmKHyjVwcDB/cIDnT2Asg8lZBjWVILbyb7OA6VtyDm+UC7Y5p2Irs9RVVZN/m/H7PUfs+k5NLsz2dzwcgGy7hKSHf2FXAK5YIumzBxJ/UZXPQKHTRS6fImdNApCjhtAoGBAIozUA5jLaXS3xNZ+FiYUl/3Pds+Y5DrLSDXuQ6qZlXGJniILY+yASyZnSgrgQnYuW6EMzuKSs94fSQlNGLR0WcQZyvbIsphKEZW4Es4b1LxBHgl2R0ht9QYT9lccQGnFq3d7KD+bmXVpbPXlsi+4qNSuZYCMEPOiYKWCv6+yA7/",
//            'merchant_private_key' => "MIIEpAIBAAKCAQEAoeGrkQY1xSd5dqGIMBXs7Lr6WX0wBQWEIqs0MlU9dDmMXflom//B7vkSBIzk/t1Mf8ZnzQ7oQl7wYfbYfWPjDKD2Vt/QDluPXql4nAEqAs78PoErRzu6pePYBzfuiv3aOzXX88033niXLyNYAZtt6GiynG/275yrqCrRD2uOTHqL5/QY4JuPBQ2D2ekR4oRZl23/2J7k/fMsm3j/VVuTbU3OtUPzHwT1oXivbjqNcscXk3nH27IrxuzZet8jrn7x7BB9J2DhEN0yBa5j8EdttYf4hmR0KWf4YgUDHVhfF43fAAdS6eXUnZH23cY0xl/t3dGB7dhYfK82joR/xqhKxwIDAQABAoIBAD/JjLE4oTduto3oWKGaAiHGC5/3lFRmOArxK0AqFm0VwBnHjVDrFp/zxQXF/vXufZZbt8s1dI+ymUYA3o2KLo1rX6Ybyv/dVEEYxk2yp7szlHGMtGD9SC7V+OnfQD/Eh5mNPerzknA3VFtese7wL/7EQp0iAUupkHpTZM2OtCOuBa+0wtL8sAUGPTgMT8qntMUUDqZfyzEbOO54z0CQCmY/bPotBihPg9TKbMZRe6QFtWGs8jYigTd8jkMSa/rgJ54FH+LGSZQowNo7SuXgMHT5aTqo7I8oD7QBG9g7n+3U3mpurOEKW1vRYlPeVXUcItbdfCtawsscvsrCS59I8wECgYEA0h8LrhuSTpkQm7pL2hvO/6uNCvkzPiWxD5U9Ylm7ASZijDEfDATM4cZ/LzZz0nf77GyNR3J7Zevgt15v4gYw1+hPmiWHiI+Uq27uMToDC97yC18nZk0S74+X6/e1W39na45BFTR+uA3LJ4ldMJR0L0oOHmc7DjfnDJSyXwh4ArsCgYEAxTo2RUKpa4wfx3dkCi96CTg+BmASBTXGnRY8RS1wPqBHYarnrNbArk0ItkwYjgmwlkz3uXsXItyn1VizT3vF/NkefRmUYrYT+bYxtqprfyFaTleYBqSdDkD4gxG8eD03xuGf8WtCWWhQgOxaphqWzuJugE49B+kb0CCC6syTtWUCgYEAuBp3k2+Y+8Dlam0KtOoUrrNS+0Bpg5Rm1S6AAEJ6tYE6a3dGqrMyCMhw+58MUGbMltRt3SITuLO5yzC1eybNeP+Vb4xeemrDaonhrcQUkzwee5Q940a4xqncBOafQrIYnYagw8GhHgcH73ZN7pjETALo5/6vIcAdc0p+FwJGlK0CgYEAgMW/RX1+7M9yuGPFl7jj7z0fkj4LxgcUhOBNvcUrMlioAqb52SnmaGe3tn780VCWUawzWpF5y7NSicP+X3krDioc9AVNkx3a8QH6d4/R3BHEyr36buvv96yIFdvwuHkP//S034Xurj2jwmmPzkAyEyZYi8GJq66SEFQJ5pBoKNkCgYB9qhqT2xStred9ooYpKNH3tPmzSUOC72wMlrcvLwV+N1QBZXNGWeJRxYsqjA36Fn2C1Ha/UKY99RyBnA+DQWEdEucv7UurILqgcqOQSg5C1FMXGF/Lm/cm5LaEAa7U8jbeu6XJw2H+vgyV1YbIlToiJ/evn6MA5G5kbpDVv01Ntg==",
            //异步通知地址
            'notify_url' => "http://system.kuaikake.cn/api/index/notify_url",
            //同步跳转
            'return_url' => "http://system.kuaikake.cn/api/index/return_url",
            //编码格式
            'charset' => "UTF-8",
            //签名方式
            'sign_type'=>"RSA2",
            //支付宝网关
            'gatewayUrl' => "https://openapi.alipay.com/gateway.do",
//            'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",
            //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
            'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAjKH8QespGqD/A8h27wWHKMrDvHkoBU8deRAS4Z0URQDjyZ4Wj/OPL6usxvheJlQgx9xT8VkbzgMLke9HnssabteLcpR3dD2fkb0Aqc7gQX7eRFhFdSFsjO0Gx7XVwBXCPP4yAvtvgTN1bVAH7UN0tya7mPWvweLRUIR7xsZnInv4joRnXNOEzMlr91zBKQlthtlxlLLjGyebomg5Ko4uZhTc2xmnGOM7CJrJAFyVTl0PLH9OuVWGwboy450FgqIz4XG1Qk9kvQNM6TJ5tZYQnr0XpmZH66pkDUB8CZ51i45I+OKjM0uN1g00fydwr2OehYuBLD2+cFs7jvTyTzIVsQIDAQAB",
//            'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAtYJdxDZvtPTir8hlzNPsrAwZdmVaczMtYVz6ViqQpPeVwKYYinKYAeIVGU3jB+yu3cNFEZtsq7piyXuMlZl0EIGu70fEovdPBG+rt9JWJyzSWY7A/J7HcB0WJ5uUce+ejYm9rzmRi1OKzaaI4kiCEkIB4xPNIuQkHj7xbrfXVT4LBFeX0O+disUK4aIGh2So1G3EtB8g9y+CSIzJYvztwkwz37UQGnsKMjSdJ9dLxm7iUrloM1RUdetJoKLQoe4GXqTdXaZKMe6O9ZLxspUYeVDJ3/YY3QX/0vitF2GjJxr3C/PZ8ooCrWnN05wfRiUAoLRdQDSgSSrZGaCMmOIIKwIDAQAB",
        );
        $price = 0.01;
        //商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no = time()+rand(10,99);
        //订单名称，必填
        $subject = "代理人";
        //付款金额，必填
        $total_amount = $price;
        //商品描述，可空
        $body = "成为代理人";
        //超时时间
        $timeout_express="1m";
        $payRequestBuilder = new \AlipayTradeWapPayContentBuilder();
        $payRequestBuilder->setBody($body);
        $payRequestBuilder->setSubject($subject);
        $payRequestBuilder->setOutTradeNo($out_trade_no);
        $payRequestBuilder->setTotalAmount($total_amount);
        $payRequestBuilder->setTimeExpress($timeout_express);
        $payResponse = new \AlipayTradeService($config);
        $result = $payResponse->wapPay($payRequestBuilder,$config['return_url'],$config['notify_url']);
        $id = input('id');
        $value = Db::name('user')->where('id',$id)->find();
        $agent = Db::name('check')->where('tel',$value['tel'])->find();
        if($agent==''){
            $data['name'] = input('name');
            $data['card'] = input('card');
            $data['level'] = '会员';
            $data['tel'] = $value['tel'];
            $data['code'] = $out_trade_no;
            $data['pid'] = $value['pid'];
            $data['money'] = 0;
            $data['time'] = time();
            Db::name('check')->insert($data);
        }else{
            $data['code'] = $out_trade_no;
            $data['time'] = time();
            Db::name('check')->where('id',$agent['id'])->update($data);
        }
        return ;

    }
   //异步通知地址
   public function notify_url(){
       $config = array (
           //应用ID,您的APPID。
           'app_id' => "2018091061358020",
           //商户私钥，您的原始格式RSA私钥
           'merchant_private_key' => "MIIEowIBAAKCAQEAsrG0C+ks6X3DgaEnj9CgQFy+0yDbn6OIVkbhxe4BtLDBgyRZOo2RVU3SBE9vw0V/+GWs1fWOhtdb6HdQjFMYJlVHAQMJWCcgUoZ68P1jgws4eD1nKkss8MbQBjAtWIMq0Tr2Ud5HvHY0guTy2FRKPEwtWgM0hp7W1i4mOwV2kpUrZfJeU1YexmTlWOgkvxkPw6SqPK7IMFtdKhq4z790pnwIwQhf7seUmyn/p1xPs7QEPLV42oU3IC6nBRIHUM2SrpBEYM5w+BZ2tQOqVjnU3O+JAMQg/zcfmz2M7w7+AC+GPhxzvRYRpUVjtTRzeRL88elTpN1plP7u3Li3zutg0wIDAQABAoIBAG8Z85KwQF9P9T1koD9XfDtoLrEALezcdUkHOxqf92mLuFU8RUoVePXrs5dAeIGVdZJQTRyeVyZZQeiQPoFwrIQw4zuq0qV/diY5PLkXIkb4s/x2WO0/3ko5ol8mlxsAOp/qI/oU1M9FQzLa/G4qQhmpV00uzvqgSXzTJqvtoqJRl8ujwBTpdLxyf0YA6k+h+UMflSwz3HS1IDSE6ZzJYkQE+oxQ6vmEQOJ1FrWIniTx2HwKSbEqkVLzBNs+L/xPqvW3Tmdi4lT25cLwNnzELQHlrw6jsd95QuK1SlYeLnMzCS/SeXcqfxmGU+tISk9n+nppR7ZClg9Ox5xh14BlztECgYEA6JDf3kq5I7GYpBFhy5ogGLfzareXiZtPOr63AlQzOeiwwswnJdDz879H94QI1obuzbp5egmNMWHHFi7SjGPqYAskEoALP9XnbamnNHU8MNMyJURxKoekIytV4aqbK/6T23qoLibIXsxHwkCqPuzKwST3QT/97GZKwompvKt2xNkCgYEAxLMvt1+f9GdDQ1sWfVRERRAyscWILXiO4JxZgK4BAVCzQETWH6uGTHjOLXDKEsbAnhq6kVgbgpoyA3TQ51rWHzKHE9+2IcxpSKBEVRPBwxYoA1nlIDoY7iz34MY69WaEbM3/pqztelQdqC4dt/oiArOCMg/EHQbdy92WrMRXF4sCgYBx6SP92VO9a+t70Re5hhBaix8rEimjOPMhrvAsr9k3cAXEJAK4vxP+O56gWYeKrCSjl8aQQ8Vofp/o+Z4fLYFK8aoFdboM1lS4nfRL+XA16fwRzOgxEKcQotU55zqB3fvF8NoXYN+brysmtk2s7IaS++wvhYx2EdRkXNFKnCFcYQKBgEpU5I1JZ6r2cua7P05xT7NBocaoPQPh8SxUKUaFev/CPVrmKHyjVwcDB/cIDnT2Asg8lZBjWVILbyb7OA6VtyDm+UC7Y5p2Irs9RVVZN/m/H7PUfs+k5NLsz2dzwcgGy7hKSHf2FXAK5YIumzBxJ/UZXPQKHTRS6fImdNApCjhtAoGBAIozUA5jLaXS3xNZ+FiYUl/3Pds+Y5DrLSDXuQ6qZlXGJniILY+yASyZnSgrgQnYuW6EMzuKSs94fSQlNGLR0WcQZyvbIsphKEZW4Es4b1LxBHgl2R0ht9QYT9lccQGnFq3d7KD+bmXVpbPXlsi+4qNSuZYCMEPOiYKWCv6+yA7/",
           //异步通知地址
           'notify_url' => "http://system.kuaikake.cn/api/index/notify_url",
           //同步跳转
           'return_url' => "http://system.kuaikake.cn/api/index/return_url",
           //编码格式
           'charset' => "UTF-8",
           //签名方式
           'sign_type'=>"RSA2",
           //支付宝网关
           'gatewayUrl' => "https://openapi.alipay.com/gateway.do",
           //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
           'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAjKH8QespGqD/A8h27wWHKMrDvHkoBU8deRAS4Z0URQDjyZ4Wj/OPL6usxvheJlQgx9xT8VkbzgMLke9HnssabteLcpR3dD2fkb0Aqc7gQX7eRFhFdSFsjO0Gx7XVwBXCPP4yAvtvgTN1bVAH7UN0tya7mPWvweLRUIR7xsZnInv4joRnXNOEzMlr91zBKQlthtlxlLLjGyebomg5Ko4uZhTc2xmnGOM7CJrJAFyVTl0PLH9OuVWGwboy450FgqIz4XG1Qk9kvQNM6TJ5tZYQnr0XpmZH66pkDUB8CZ51i45I+OKjM0uN1g00fydwr2OehYuBLD2+cFs7jvTyTzIVsQIDAQAB",
       );
        $arr=$_POST;
        $alipaySevice = new \AlipayTradeService($config);
        $alipaySevice->writeLog(var_export($_POST,true));
        $result = $alipaySevice->check($arr);

        /* 实际验证过程建议商户添加以下校验。
        1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
        2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
        3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
        4、验证app_id是否为该商户本身。
        */
        if($result) {//验证成功
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //请在这里加上商户的业务逻辑程序代
            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——

            //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表

            //商户订单号
        $out_trade_no = $_POST['out_trade_no'];
        //支付宝交易号
        $trade_no = $_POST['trade_no'];
        //交易状态
        $trade_status = $_POST['trade_status'];
        if($_POST['trade_status'] == 'TRADE_FINISHED') {

		//判断该笔订单是否在商户网站中已经做过处理
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//请务必判断请求时的total_amount与通知时获取的total_fee为一致的
			//如果有做过处理，不执行商户的业务程序

		//注意：
		//退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
    }
    else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
		//判断该笔订单是否在商户网站中已经做过处理
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//请务必判断请求时的total_amount与通知时获取的total_fee为一致的
			//如果有做过处理，不执行商户的业务程序
		//注意：
		//付款完成后，支付宝系统发送该交易状态通知
    }
	//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

	echo "success";		//请不要修改或删除

}else {
    //验证失败
    echo "fail";	//请不要修改或删除

}
   }
   //同步通知页面
   public function return_url(){
       $config = array (
           //应用ID,您的APPID。
           'app_id' => "2018091061358020",
           //商户私钥，您的原始格式RSA私钥
           'merchant_private_key' => "MIIEowIBAAKCAQEAsrG0C+ks6X3DgaEnj9CgQFy+0yDbn6OIVkbhxe4BtLDBgyRZOo2RVU3SBE9vw0V/+GWs1fWOhtdb6HdQjFMYJlVHAQMJWCcgUoZ68P1jgws4eD1nKkss8MbQBjAtWIMq0Tr2Ud5HvHY0guTy2FRKPEwtWgM0hp7W1i4mOwV2kpUrZfJeU1YexmTlWOgkvxkPw6SqPK7IMFtdKhq4z790pnwIwQhf7seUmyn/p1xPs7QEPLV42oU3IC6nBRIHUM2SrpBEYM5w+BZ2tQOqVjnU3O+JAMQg/zcfmz2M7w7+AC+GPhxzvRYRpUVjtTRzeRL88elTpN1plP7u3Li3zutg0wIDAQABAoIBAG8Z85KwQF9P9T1koD9XfDtoLrEALezcdUkHOxqf92mLuFU8RUoVePXrs5dAeIGVdZJQTRyeVyZZQeiQPoFwrIQw4zuq0qV/diY5PLkXIkb4s/x2WO0/3ko5ol8mlxsAOp/qI/oU1M9FQzLa/G4qQhmpV00uzvqgSXzTJqvtoqJRl8ujwBTpdLxyf0YA6k+h+UMflSwz3HS1IDSE6ZzJYkQE+oxQ6vmEQOJ1FrWIniTx2HwKSbEqkVLzBNs+L/xPqvW3Tmdi4lT25cLwNnzELQHlrw6jsd95QuK1SlYeLnMzCS/SeXcqfxmGU+tISk9n+nppR7ZClg9Ox5xh14BlztECgYEA6JDf3kq5I7GYpBFhy5ogGLfzareXiZtPOr63AlQzOeiwwswnJdDz879H94QI1obuzbp5egmNMWHHFi7SjGPqYAskEoALP9XnbamnNHU8MNMyJURxKoekIytV4aqbK/6T23qoLibIXsxHwkCqPuzKwST3QT/97GZKwompvKt2xNkCgYEAxLMvt1+f9GdDQ1sWfVRERRAyscWILXiO4JxZgK4BAVCzQETWH6uGTHjOLXDKEsbAnhq6kVgbgpoyA3TQ51rWHzKHE9+2IcxpSKBEVRPBwxYoA1nlIDoY7iz34MY69WaEbM3/pqztelQdqC4dt/oiArOCMg/EHQbdy92WrMRXF4sCgYBx6SP92VO9a+t70Re5hhBaix8rEimjOPMhrvAsr9k3cAXEJAK4vxP+O56gWYeKrCSjl8aQQ8Vofp/o+Z4fLYFK8aoFdboM1lS4nfRL+XA16fwRzOgxEKcQotU55zqB3fvF8NoXYN+brysmtk2s7IaS++wvhYx2EdRkXNFKnCFcYQKBgEpU5I1JZ6r2cua7P05xT7NBocaoPQPh8SxUKUaFev/CPVrmKHyjVwcDB/cIDnT2Asg8lZBjWVILbyb7OA6VtyDm+UC7Y5p2Irs9RVVZN/m/H7PUfs+k5NLsz2dzwcgGy7hKSHf2FXAK5YIumzBxJ/UZXPQKHTRS6fImdNApCjhtAoGBAIozUA5jLaXS3xNZ+FiYUl/3Pds+Y5DrLSDXuQ6qZlXGJniILY+yASyZnSgrgQnYuW6EMzuKSs94fSQlNGLR0WcQZyvbIsphKEZW4Es4b1LxBHgl2R0ht9QYT9lccQGnFq3d7KD+bmXVpbPXlsi+4qNSuZYCMEPOiYKWCv6+yA7/",
           //编码格式
           'charset' => "UTF-8",
           //签名方式
           'sign_type'=>"RSA2",
           //支付宝网关
           'gatewayUrl' => "https://openapi.alipay.com/gateway.do",
           //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
           'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAjKH8QespGqD/A8h27wWHKMrDvHkoBU8deRAS4Z0URQDjyZ4Wj/OPL6usxvheJlQgx9xT8VkbzgMLke9HnssabteLcpR3dD2fkb0Aqc7gQX7eRFhFdSFsjO0Gx7XVwBXCPP4yAvtvgTN1bVAH7UN0tya7mPWvweLRUIR7xsZnInv4joRnXNOEzMlr91zBKQlthtlxlLLjGyebomg5Ko4uZhTc2xmnGOM7CJrJAFyVTl0PLH9OuVWGwboy450FgqIz4XG1Qk9kvQNM6TJ5tZYQnr0XpmZH66pkDUB8CZ51i45I+OKjM0uN1g00fydwr2OehYuBLD2+cFs7jvTyTzIVsQIDAQAB",
       );
        $arr=$_GET;
        $alipaySevice = new \AlipayTradeService($config);
        $alipaySevice->writeLog(var_export($_GET,true));
        $result = $alipaySevice->check($arr);
        /* 实际验证过程建议商户添加以下校验。
        1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
        2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
        3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
        4、验证app_id是否为该商户本身。
        */
        if($result) {//验证成功
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //请在这里加上商户的业务逻辑程序代
            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——

            //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表

            //商户订单号
            $out_trade_no = $_GET['out_trade_no'];
            //支付宝交易号
            $trade_no = $_GET['trade_no'];
            //交易状态

            $data = Db::name('check')->where('code',$out_trade_no)->find();
            $data['code'] = 0;
            $save = Db::name('check')->where('code',$out_trade_no)->update($data);
            new_table($data['id']);
            $count =  Db::name('check')->where('pid',$data['pid'])->count();
            if($count >= 50){
                $pid = Db::name('check')->where('id',$data['pid'])->find();
                if($pid['level'] != '团长'){
                    $pid['level'] = '团长';
                    Db::name('check')->where('id',$pid['id'])->update($pid);
                }
            }
	        echo "success";		//请不要修改或删除

        }else {
            //验证失败
            echo "fail";	//请不要修改或删除

        }
   }
   //修改昵称
   public function setName(){
       $id = input('id');
       $data['name'] = input('name');
//       return json_encode($id);
       $saveId = Db::name('user')->where('id',$id)->update($data);
       return json_encode($saveId);
   }
   //修改昵称
   public function setAgent(){
       $id = input('id');
       $value = Db::name('user')->where('id',$id)->find();
       $data['name'] = input('name');
       $data['card'] = input('card');
       $data['level'] = '会员';
       $data['tel'] = $value['tel'];
       $data['pid'] = $value['pid'];
       $data['code'] = 0;
       $data['money'] = 0;
       $data['time'] = time();
       $saveId = Db::name('check')->insert($data);
       $did = Db::name('check')->getLastInsID();
       new_table($did);
       $count =  Db::name('check')->where('pid',$data['pid'])->count();
       $system = Db::name('system')->find();
       if($count >= $system['perple']){
           $pid = Db::name('check')->where('id',$data['pid'])->find();
           if($pid['level'] != '团长'){
               $pid['level'] = '团长';
               Db::name('check')->where('id',$pid['id'])->update($pid);
           }
       }
       return json_encode($saveId);
   }
   //修改性别
   public function setSex(){
       $id = input('id');
       $data['sex'] = input('sex');
       $saveId = Db::name('user')->where('id',$id)->update($data);
       return json_encode($saveId);
   }
   //添加订单
   public function setOrder(){
       $data['user'] = input('user');
       $data['check'] = input('check');
       $data['goods'] = input('goods');
       $data['name'] = input('name');
       $data['card'] = input('card');
       $data['tel'] = input('tel');
       $data['status'] = 0;
       $data['time'] = time();
       $saveId = Db::name('order')->insert($data);
       if($saveId){
           $goods = Db::name('goods')->where('id',$data['goods'])->find();
           $saveId = $goods['url'];
       }
       return json_encode($saveId);
   }
   //获取用户
   public function getUser(){
       $tel = input('tel');
//       return $tel;
       $pwd = md5(input('pwd'));
       $data = Db::name('user')->where('tel',$tel)->find();
       if($data == null){
           $data['status'] = 2;
       }else{
           if($data['pwd'] == $pwd){
               $data['status'] = 1;
               $agent = Db::name('check')->where('tel',$tel)->find();
               if($agent !=  null){
                   $data['check'] = 1;
                   $data['code'] = $agent['code'];
               }else{
                   $data['check'] = 0;
               }
           }else{
               $data['status'] = 0;
           }
       }
       return json_encode($data);
   }
   //根据id获取用户
   public function getUserById(){
       $id = input('id');
       $data = Db::name('user')->where('id',$id)->find();
       $agent = Db::name('check')->where('tel',$data['tel'])->find();
       if($agent !=  null){
           $data['check'] = 1;
           $data['code'] = $agent['code'];
       }else{
           $data['check'] = 0;
       }
       return json_encode($data);
   }
   //根据Banner
   public function getBanner(){
       $data = Db::name('dormitory')->select();
       return json_encode($data);
   }
   //获取所有产品
    public function getGoods(){
       $class = input('class');
       $where = '';
       $class && $where = 'class = ' . $class;
       $data = Db::name('goods')->where($where)->select();
       foreach ($data as $k=>$v){
           $num = Db::name('order')->where('goods',$v['id'])->count();
           $data[$k]['num'] = $num;
       }
        return json_encode($data);
    }
    //根据id获取产品
    public function getGoodsById(){
       $id = input('id');
       $data = Db::name('goods')->where('id',$id)->select();
        return json_encode($data);
    }
    //发送短信
    public function setSms(){
       vendor('aliyun-dysms-php-sdk.api_demo.SmsDemo');
        $number = input('number');
//        $number = '18716619972';
        $code = rand(1000,9999);
        $response = \SmsDemo::sendSms($number,$code);
        $data['code'] = $code;
        $data['response'] = $response;
        return json_encode($data);
    }
    //获取所有资讯
    public function getNews(){
       $data = Db::name('news')->select();
       foreach ($data as $k=>$v){
           $data[$k]['time'] = date('Y-m-d',$v['time']);
       }
        return json_encode($data);
    }
    //设置点击数
    public function setBrowse(){
       $id = input('id');
       $data = Db::name('news')->where('id',$id)->find();
       $data['browse'] +=1;
       $saveId = Db::name('news')->where('id',$id)->update($data);
        return json_encode($saveId);
    }
    //根据id获取资讯
    public function getNewsById(){
       $id = input('id');
       $data = Db::name('news')->where('id',$id)->find();
       $data['time'] = date('Y-m-d',$data['time']);
       return json_encode($data);
    }
    //设置头像
    public function setImg(){
        $id = input('id');
        $file = request()->file('file');
        $info = $file->move('uploads');
        $data['img'] = 'uploads/'.$info->getSaveName();
        $saveId = Db::name('user')->where('id',$id)->update($data);
        $data['status'] = $saveId;
        return json_encode($data);
    }
    //获取提现明细
    public function getForward(){
       $id = input('id');
       $data = Db::name($id . '_detail')->where('class',0)->select();
        return json_encode($data);
    }
    //获取业绩明细
    public function getIncome(){
       $id = input('id');
//       $id = 4;
       $user = Db::name('user')->where('id',$id)->find();
       $agent = Db::name('check')->where('tel',$user['tel'])->find();
       for($i = 1 ; $i<=date('m'); $i ++){
           $time = strtotime(date('Y-'.$i.'-01'));
           $end = strtotime(date('Y-'.($i+1).'-01'));
           $where = 'time >= ' . $time .' and time < ' .$end;
           $detail = Db::name( $agent['id']. '_detail')->where('class != 0')->where($where)->select();
           foreach ($detail as $k=>$v){
               if($v['class'] == 1){
                   $goods = Db::name('goods')->where('id',$v['goods_id'])->find();
                   $detail[$k]['goods_name'] = $goods['name'];
                   $detail[$k]['goods_img'] = $goods['img'];
                   $detail[$k]['source'] = "直推";
               }elseif($v['class'] == 2){
                   $detail[$k]['source'] = "下级分润";
               }else{
                   $sun = Db::name('check')->where('id',$v['source'])->find();
                   $s_user = Db::name('user')->where('tel',$sun['tel'])->find();
                   $goods = Db::name('goods')->where('id',$v['goods_id'])->find();
                   if($sun['pid'] != $agent['id']){
                       $detail[$k]['source'] = "二级提成";
                       $detail[$k]['name'] = $s_user['name'];
                       $detail[$k]['goods_name'] = $goods['name'];
                       $detail[$k]['goods_img'] = $goods['img'];
                   }else{
                       $detail[$k]['source'] = "一级提成";
                       $detail[$k]['name'] = $s_user['name'];
                       $detail[$k]['goods_name'] = $goods['name'];
                       $detail[$k]['goods_img'] = $goods['img'];
                   }
               }
               $detail[$k]['time'] = date('m-d H:i',$v['time']);
           }
           $data[$i]['detail'] = $detail;
           $value['share'] = Db::name( $agent['id']. '_detail')->where('class = 2')->where($where)->sum('num');
           $value['extract'] = Db::name( $agent['id']. '_detail')->where('class = 3 or class = 1')->where($where)->sum('num');
           $data[$i]['value'] = $value;
       }
//       dump($data);die;
       return json_encode($data);
    }
    //获取团队
    public function getTeam(){
       $id = input('id');
       $data = Db::name('check')->where('pid',$id)->select();
       foreach ($data as $k=>$v){
           $user = Db::name('user')->where('tel',$v['tel'])->find();
           $data[$k]['img'] = $user['img'];
           $data[$k]['name'] = $user['name'];
       }
       $data['count'] = Db::name('check')->where('pid',$id)->count();
       return json_encode($data);
    }
    //获取代理人
    public function getAgent()
    {
        $id = input('id');
        $data = Db::name('user')->where('id', $id)->find();
        $agent = Db::name('check')->where('tel', $data['tel'])->find();
        $data['level'] = $agent['level'];
        $data['code'] = $agent['code'];
        return json_encode($data);
    }
    //获取排行榜
    public function getRanking(){
        $m = date('m')-1;
        $time = strtotime(date('Y-'.$m.'-01'));
        $end = strtotime(date('Y-m-01'));
        $ranking = Db::name('count')->where('time',$time)->count();
        if($ranking == 0) {
            Db::name('count')->delete(true);
            $id = Db::name('check')->column('id');
            $k = 0;
            $where = 'time >= ' . $time .' and time <= ' .$end;
            foreach ($id as $v) {
                $detail = Db::name($v . '_detail')->where('class', 1)->where($where)->sum('num');
                $data[$k]['user'] = $v;
                $data[$k]['num'] = $detail;
                $k++;
            }
            for($i = 0; $i < count($data); $i++){
                for($j = $i+1; $j < count($data); $j++){
                    if ($data[$i]['num'] < $data[$j]['num']) {
                        $key = $data[$i];
                        $data[$i] = $v;
                        $data[$j] = $key;
                    }
                }
            }
            foreach ($data as $k => $v) {
                $value = $v;
                $value['id'] = $k + 1;
                $value['stauts'] = 1;
                if ($k < 10) {
                    $value['stauts'] = 0;
                }
                $value['time'] = $time;
                Db::name('count')->insert($value);
            }
        }
        $data = Db::name('count')->select();
        foreach ($data as $k=>$v){
            $agent = Db::name('check')->where('id',$v['user'])->find();
            $user = Db::name('user')->where('tel',$agent['tel'])->find();
            $data[$k]['name'] = $agent['name'];
            $data[$k]['img'] = $user['img'];
        }
        return json_encode($data);
    }
    public function getUrl(){
        vendor("phpqrcode.phpqrcode");
        $url = input('url');
        $data =urldecode($url);
        $level = 'L';// 纠错级别：L、M、Q、H
        $size =4;// 点的大小：1到10,用于手机端4就可以了
        $QRcode = new \QRcode();
        ob_start();
        $QRcode->png($data,false,$level,$size,2);
        $imageString = base64_encode(ob_get_contents());
        ob_end_clean();
        return $imageString;
    }
    //获取未结算单数和已结算金额
    public function getBalance(){
       $id = input('id');
       $user = Db::name('user')->where('id',$id)->find();
       $agent = Db::name('check')->where('tel',$user['tel'])->find();
       $data['money'] = $agent['money'];
       $num = Db::name($agent['id'] . '_detail')->where('class = 1 and source = ' . $agent['id'])->sum('num');
       $data['num'] = $num;
       $count = Db::name('order')->where('status = 0 and check = ' . $agent['id'])->count();
       $data['count'] = $count;
       return json_encode($data);
    }
    //获取本月团队业绩
    public function getMonth(){
       $id = input('id');
//       $id = 4;
       $m = date('m')+1;
       $time = strtotime(date('Y-m-01'));
       $end = strtotime(date('Y-'.$m.'-01'));
       $where = 'time >= ' . $time .' and time < ' .$end;
       $parent = Db::name('user')->where('id',$id)->find();
       $parent = Db::name('check')->where('tel',$parent['tel'])->find();
       $agent = Db::name('check')->where('id = '.$parent['id'].' or pid = '.$parent['id'])->select();
       foreach ($agent as $k =>$v){
           $user = Db::name('user')->where('tel',$v['tel'])->find();
           $agent[$k]['img'] = $user['img'];
           $agent[$k]['name'] = $user['name'];
           $agent[$k]['num'] = Db::name($v['id'].'_detail')->where('class = 1 and source = '.$v['id'])->where($where)->sum('num');
       }
        for($i = 0; $i < count($agent); $i++){
            for($j = $i+1; $j < count($agent); $j++){
                if ($agent[$i]['num'] < $agent[$j]['num']) {
                    $key = $agent[$i];
                    $agent[$i] = $agent[$j];
                    $agent[$j] = $key;
                }
            }
        }
//        dump($check);
       return json_encode($agent);
    }
    public function getApply(){
        $id = input('id');
        $data = Db::name('order')->where(['check'=>$id])->select();
        if($data != ''){
            foreach ($data as $k=>$v){
                $goods = Db::name('goods')->where('id',$v['goods'])->find();
                $data[$k]['goods'] = $goods['name'];
                $data[$k]['time'] = date('Y-m-d',$v['time']);
            }
        }
        return json_encode($data);

    }
    public function getImg(){
        $data = Db::name('system')->find();
//        $image_file = 'http://system.kuaikake.cn/'.$data['img'];
        $data['img'] = 'http://system.kuaikake.cn/'.$data['img'];
//        $data['img'] = base64_decode($image_file);
//        return json_encode($data);
        return $_GET['callback']."(".json_encode($data).")";
    }
    public function getShare(){
        $data = Db::name('system')->find();
//        $image_file = 'http://system.kuaikake.cn/'.$data['img'];
        $data['share'] = 'http://system.kuaikake.cn/'.$data['share'];
//        $data['img'] = base64_decode($image_file);
//        return json_encode($data);
        return $_GET['callback']."(".json_encode($data).")";
    }
}