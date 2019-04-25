<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Goods;
use App\Model\Weixin;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Support\Str;
use App\Model\Wxtext;
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

        //判断
        if($res->MsgType=='event'){
            if($res->Event=='subscribe'){// echo '关注事件';
                $local_info = Wxtext::where('openid',$oid)->first();
                if($local_info){
                    $dd1 = ['sub_status'=>1];
                    Wxtext::where('openid',$oid)->update($dd);
                    echo "<xml><ToUserName><![CDATA[$oid]]></ToUserName><FromUserName><![CDATA[$gzhid]]></FromUserName><CreateTime>".time()."</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[欢迎回来  {$local_user->nickname}]]></Content></xml>";
                }else{
                    $l = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".get_wx_access()."&openid=".$res->FromUserName."&lang=zh_CN";
                    $data = file_get_contents($l);
                    $u = json_decode($data,true);
                    $date = [
                        'openid'=>$res->FromUserName,
                        'nickname'=>$u['nickname'],
                        'sex'=>$u['sex'],
                        'headimgurl'=>$u['headimgurl']
                    ];
                    Wxtext::insert($date);
                    echo "<xml><ToUserName><![CDATA[$oid]]></ToUserName><FromUserName><![CDATA[$gzhid]]></FromUserName><CreateTime>".time()."</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[欢迎关注{$u['nickname']}]]></Content></xml>";
                }
            }else{
                $dd = ['sub_status'=>0];
                $ddd = Wxtext::where('openid',$oid)->update($dd);
                // dd($ddd);
            }
        }else if($res->MsgType=='text'){
            if(strpos($res->Content,'+天气')){
                $rrr = explode('+',$res->Content)[0];
                // echo($rrr);die;
                $tq_u = "https://free-api.heweather.net/s6/weather/now?key=HE1904161048191969&location=".$rrr;
                $tq_data = json_decode(file_get_contents($tq_u),true);
                // print_r($tq_data);die;
                if($tq_data['HeWeather6'][0]['status']=='ok'){
                    $cond_txt = $tq_data['HeWeather6'][0]['now']['cond_txt']; //天气情况
                    $tmp = $tq_data['HeWeather6'][0]['now']['tmp']; //摄氏度
                    $hum = $tq_data['HeWeather6'][0]['now']['hum']; //湿度
                    $wind_dir = $tq_data['HeWeather6'][0]['now']['wind_dir']; //风向
                    $wind_sc = $tq_data['HeWeather6'][0]['now']['wind_sc']; //风力
                    $res_tq_data = '天气情况:'.$cond_txt."\n".'摄氏度:'.$tmp."\n".'湿度:'.$hum."\n".'风向:'.$wind_dir."\n".'风力:'.$wind_sc;
                    // echo $res_tq_data;die;
                    echo "<xml>
                            <ToUserName><![CDATA[$oid]]></ToUserName>
                            <FromUserName><![CDATA[$gzhid]]></FromUserName>
                            <CreateTime>".time()."</CreateTime>
                            <MsgType><![CDATA[text]]></MsgType>
                            <Content><![CDATA[".$res_tq_data."]]></Content>
                          </xml>";
                }else{
                    echo "<xml>
                            <ToUserName><![CDATA[$oid]]></ToUserName>
                            <FromUserName><![CDATA[$gzhid]]></FromUserName>
                            <CreateTime>".time()."</CreateTime>
                            <MsgType><![CDATA[text]]></MsgType>
                            <Content><![CDATA[城市名称不正确！！]]></Content>
                          </xml>";
                }

            }else if($res->Content=='最新商品'){
                        $goodsinfo = Goods::first();
                        echo "<xml><ToUserName><![CDATA[$oid]]></ToUserName><FromUserName><![CDATA[$gzhid]]></FromUserName><CreateTime>".time()."</CreateTime><MsgType><![CDATA[news]]></MsgType><ArticleCount>1</ArticleCount><Articles><item><Title><![CDATA[".$goodsinfo->goods_name."]]></Title><Description><![CDATA[iphone不好用了，能支持国产了！]]></Description><PicUrl><![CDATA[https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1556079981426&di=741f4c19088db58ea3339840587ba02f&imgtype=0&src=http%3A%2F%2Fwww.quaintfab.com%2FUploads%2Fimage%2F20160112%2F20160112032125_79518.jpg]]></PicUrl><Url><![CDATA[http://1809zhaokai.comcto.com/weixin/goods_detail/".$goodsinfo->id."]]></Url></item></Articles></xml>";
//                echo "<xml><ToUserName><![CDATA[$oid]]></ToUserName><FromUserName><![CDATA[$gzhid]]></FromUserName><CreateTime>".time()."</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[请稍等，马上查出来！！]]></Content></xml>";
            }else{
//                        echo "<xml><ToUserName><![CDATA[$oid]]></ToUserName><FromUserName><![CDATA[$gzhid]]></FromUserName><CreateTime>".time()."</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[暂无有效信息！！]]></Content></xml>";
//                    }
//                }
                $text_data = [
                    'openid'=>$res->FromUserName,
                    'gzhid'=>$res->ToUserName,
                    'msgid'=>$res->MsgId,
                    'text'=>$res->Content,
                    'create_t'=>$res->CreateTime
                ];
                WxText::insert($text_data);
                echo "<xml>
                            <ToUserName><![CDATA[$oid]]></ToUserName>
                            <FromUserName><![CDATA[$gzhid]]></FromUserName>
                            <CreateTime>".time()."</CreateTime>
                            <MsgType><![CDATA[text]]></MsgType>
                            <Content><![CDATA[我们已收到您的消息,亲,稍等]]></Content>
                          </xml>";
            }
        }else if($res->MsgType=='voice'){
            $url ="https://api.weixin.qq.com/cgi-bin/media/get?access_token=".get_wx_access()."&media_id=".$res->MediaId;
            $url1 = file_get_contents($url);
            $file_name = time().mt_rand(11111,99999).'.amr';
            $re = file_put_contents('wx/voice/'.$file_name,$url1);
            $voice_data = [
                'openid'=>$res->FromUserName,
                'gzhid'=>$res->ToUserName,
                'msgid'=>$res->MsgId,
                'mediaid'=>$res->MediaId,
                'url'=>'wx/voice/'.$file_name,
            ];
            Wxtext::insert($voice_data);
            echo "<xml>
                            <ToUserName><![CDATA[$oid]]></ToUserName>
                            <FromUserName><![CDATA[$gzhid]]></FromUserName>
                            <CreateTime>".time()."</CreateTime>
                            <MsgType><![CDATA[text]]></MsgType>
                            <Content><![CDATA[我们已收到您的语音消息,亲,稍等]]></Content>
                          </xml>";
        }else if($res->MsgType=='image'){
            $img = "https://api.weixin.qq.com/cgi-bin/media/get?access_token=".get_wx_access()."&media_id=".$res->MediaId;
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
            $image_data = [
                'openid'=>$res->FromUserName,
                'gzhid'=>$res->ToUserName,
                'msgid'=>$res->MsgId,
                'mediaid'=>$res->MediaId,
                'url'=>$img_name,
            ];
            Wxtext::insert($image_data);
            $rs = Storage::put($img_name,$response->getbody());
            if($rs){
                echo "<xml><ToUserName><![CDATA[$oid]]></ToUserName><FromUserName><![CDATA[$gzhid]]></FromUserName><CreateTime>".time()."</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[保存ok！]]></Content></xml>";
            }else{
                echo "<xml><ToUserName><![CDATA[$oid]]></ToUserName><FromUserName><![CDATA[$gzhid]]></FromUserName><CreateTime>".time()."</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[保存失败！]]></Content></xml>";
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
        $a_config = $this->test();
//        print_r($a_config);die;
        return view("goods.detail",['data'=>$data,'a_config'=>$a_config]);
    }
    function test()
    {
        //签名
        $sign = get_sign();
        $nonceStr = Str::random(10);//随机字符串
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
        return $config;
    }
    public function getuser()
    {
//      print_r($_GET);
        //用code 换取 网页access_token
        $url= "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".env('WX_APPID')."&secret=".env('WX_APPSECRET')."&code=".$_GET['code']."&grant_type=authorization_code";
        $access = json_decode(file_get_contents($url),true);
//        通过access_token和openid拉取用户信息
        $access_token = $access['access_token'];
        $openid = $access['openid'];

        $u = "https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
        $userinfo = json_decode(file_get_contents($u),true);
//        检测该用户存在不存在
        $res = Weixin::where('openid',$userinfo['openid'])->first();
        if(!$res){
            $info = [
                'openid'=>$userinfo['openid'],
                'nickname'=>$userinfo['nickname'],
                'sex'=>$userinfo['sex'],
                'headimgurl'=>$userinfo['headimgurl'],
                'city'=>$userinfo['city'],
                'province'=>$userinfo['province'],
                'country'=>$userinfo['country']
            ];
            Weixin::insert($info);
            echo "欢迎登陆<h3>".$userinfo['nickname']."</h3>";
        }else{
            echo "欢迎回来<h3>".$res['nickname']."</h3>";
        }
    }
}
