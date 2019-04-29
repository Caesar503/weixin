<?php
use Illuminate\Support\Facades\Redis;
    //获取accessary__token
    function get_wx_access()
    {
        $key = 'a_toke';
        $access_token = Redis::get($key);
//        echo $access_token;die;
        if($access_token){
            return $access_token;
        }else{
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".env('WX_APPID')."&secret=".env('WX_APPSECRET');
            $data = json_decode(file_get_contents($url),true);
            if(isset($data['access_token'])){
                Redis::set($key,$data['access_token']);
                Redis::expire($key,3600);
                return $data['access_token'];
            }else{
                return false;
            }
        }
    }
    //获取签名
    function get_sign()
    {
        $k = 'a_sign';
        $aa = Redis::get($k);
        if($aa){
            return $aa;
        }else{
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".get_wx_access()."&type=jsapi";
            $respon = json_decode(file_get_contents($url),true);
            if(isset($respon['ticket'])){
                Redis::set($k,$respon['ticket']);
                Redis::expire($k,3600);
                return $respon['ticket'];
            }else{
                return false;
            }
        }
    }
