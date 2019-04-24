<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Goods;
class WXController extends Controller
{
    public function get_vaild(){
        echo $_GET['echostr'];
    }
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
                $goodsinfo = Goods::first();
                echo "<xml><ToUserName><![CDATA[$oid]]></ToUserName><FromUserName><![CDATA[$gzhid]]></FromUserName><CreateTime>".time()."</CreateTime><MsgType><![CDATA[news]]></MsgType><ArticleCount>1</ArticleCount><Articles><item><Title><![CDATA[".$goodsinfo->goods_name."]]></Title><Description><![CDATA[iphone不好用了，能支持国产了！]]></Description><PicUrl><![CDATA[https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1556079981426&di=741f4c19088db58ea3339840587ba02f&imgtype=0&src=http%3A%2F%2Fwww.quaintfab.com%2FUploads%2Fimage%2F20160112%2F20160112032125_79518.jpg]]></PicUrl><Url><![CDATA[http://1809zhaokai.comcto.com/weixin/goods_detail/".$goodsinfo->id."]]></Url></item></Articles></xml>";
//                echo "<xml><ToUserName><![CDATA[$oid]]></ToUserName><FromUserName><![CDATA[$gzhid]]></FromUserName><CreateTime>".time()."</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[请稍等，马上查出来！！]]></Content></xml>";
            }else{
                echo "<xml><ToUserName><![CDATA[$oid]]></ToUserName><FromUserName><![CDATA[$gzhid]]></FromUserName><CreateTime>".time()."</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[暂无有效信息！！]]></Content></xml>";
            }
        }
    }
    public function goods_detail($id=0)
    {
        if(!$id){
            die('无此商品信息');
        }
        $data = Goods::where('id',$id)->first()->toArray();
//        dump($data);
        return view("goods.detail",['data'=>$data]);
    }
}
