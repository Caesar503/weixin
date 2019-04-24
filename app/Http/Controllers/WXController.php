<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WXController extends Controller
{
    public function post_vaild()
    {
        //获取传过来的值
        $content = file_get_contents("php://input");
        $res = simplexml_load_string($content);
//        dd($res);
        $time = date('Y-m-d H:i:s',time());
        $str = $time.$content."\n";
        // 写入日志
        file_put_contents("logs/wx_event.log",$str,FILE_APPEND);

        //用户的openid
        $oid = $res->FromUserName;
        // 公众号id
        $gzhid = $res->ToUserName;

        if($res->MsgType=='text'){
            if($res->Content=='最新商品'){
                echo "<xml>
                            <ToUserName><![CDATA[$oid]]></ToUserName>
                            <FromUserName><![CDATA[$gzhid]]></FromUserName>
                            <CreateTime>".time()."</CreateTime>
                            <MsgType><![CDATA[text]]></MsgType>
                            <Content><![CDATA[请稍等，马上查出来！！]]></Content>
                          </xml>";
            }else{
                    echo "<xml>
                            <ToUserName><![CDATA[$oid]]></ToUserName>
                            <FromUserName><![CDATA[$gzhid]]></FromUserName>
                            <CreateTime>".time()."</CreateTime>
                            <MsgType><![CDATA[text]]></MsgType>
                            <Content><![CDATA[暂无有效信息！！]]></Content>
                          </xml>";
            }
        }
    }
}
