<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Monolog\Handler\Curl;
class WxJcontroller extends Controller
{
    public function test()
    {
        //签名
        $sign = get_sign();
        $nonceStr = Str::random(9);//随机字符串
        $time = time();//当前时间戳
        $url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
//        echo $url;
//        dump($_SERVER);die;
        $str = "jsapi_ticket=".$sign."&noncestr=".$nonceStr."&timestamp=".$time."&url=".$url;
        $signature= sha1($str);

        $config = [
            'appId'=> env('WX_APPID'), // 必填，公众号的唯一标识
            'timestamp'=>$time, // 必填，生成签名的时间戳
            'nonceStr'=>$nonceStr, // 必填，生成签名的随机串
            'signature' => $signature,// 必填，签名
        ];
        return view('weixin.jssdk',['a_config'=>$config]);
    }
    //下载
    public function download()
    {
//        $url = "https://api.weixin.qq.com/cgi-bin/media/get?access_token=".get_wx_access()."&media_id=".$_GET['mid'];
//        $data = file_get_contents($url);
        $accessToken = get_wx_access();
        $savePathFile = '/weixin'.date('YmdHis').'.jpg';
        $targetName = $savePathFile;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        $fp = fopen($targetName,'wb');
        curl_setopt($ch,CURLOPT_URL,"http://file.api.weixin.qq.com/cgi-bin/media/get?access_token={$accessToken}&media_id=".$_GET['mid']);
        curl_setopt($ch,CURLOPT_FILE,$fp);
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
    }
}
