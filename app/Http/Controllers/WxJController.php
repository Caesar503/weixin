<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Support\Facades\Storage;
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
            'url'=>$url
        ];
        return view('weixin.jssdk',['a_config'=>$config]);
    }
    //下载
    public function download()
    {
//        $url = "https://api.weixin.qq.com/cgi-bin/media/get?access_token=".get_wx_access()."&media_id=".$_GET['mid'];
//        $data = file_get_contents($url);
//        file_put_contents('/weixin/',$data);
        $img = "https://api.weixin.qq.com/cgi-bin/media/get?access_token=".get_wx_access()."&media_id=".$_GET['mid'];
        $img1 = file_get_contents($img);
        $client = new Client();
        $response = $client->get(new Uri($img));
        //响应头
        $header = $response->getHeaders();
        // dd($header);
        $pp = $header['Content-disposition'][0];
        $ppp = rtrim(substr($pp,-20),'"');
        $img_name = 'weixin/'.substr(md5(time().mt_rand()),10,8).'_'.$ppp;
        // echo $img_name;
        $rs = Storage::put($img_name,$response->getbody());
    }
}
