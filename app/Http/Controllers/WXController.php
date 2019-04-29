<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Goods;
use App\Model\Weixin;
use Illuminate\Support\Str;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use Monolog\Handler\Curl;
use App\Model\Tmp;
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
            }else{
                $g_info = Goods::where('goods_name',$res->Content)->first();
                $r_info = Goods::where('id',rand(1,5))->first()->toArray();
//                dd($r_info);
                if($g_info){
                    echo "<xml><ToUserName><![CDATA[$oid]]></ToUserName><FromUserName><![CDATA[$gzhid]]></FromUserName><CreateTime>".time()."</CreateTime><MsgType><![CDATA[news]]></MsgType><ArticleCount>1</ArticleCount><Articles><item><Title><![CDATA[".$g_info->goods_name."]]></Title><Description><![CDATA[小呀嘛小二郎，巧指".$g_info->goods_name."]]></Description><PicUrl><![CDATA[".$g_info->img."]]></PicUrl><Url><![CDATA[http://1809zhaokai.comcto.com/weixin/goods_detail/".$g_info->id."]]></Url></item></Articles></xml>";
                }else{
                    echo "<xml><ToUserName><![CDATA[$oid]]></ToUserName><FromUserName><![CDATA[$gzhid]]></FromUserName><CreateTime>".time()."</CreateTime><MsgType><![CDATA[news]]></MsgType><ArticleCount>1</ArticleCount><Articles><item><Title><![CDATA[".$r_info['goods_name']."]]></Title><Description><![CDATA[小呀嘛小二郎，巧指".$r_info['goods_name']."]]></Description><PicUrl><![CDATA[".$r_info['img']."]]></PicUrl><Url><![CDATA[http://1809zhaokai.comcto.com/weixin/goods_detail/".$r_info['id']."]]></Url></item></Articles></xml>";
                }

            }
        }
        if($res->MsgType=='event'){
            //判断eventkey 存在不存在
            if(isset($res->EventKey)){
                $goodsinfo = Goods::first();
                if($res->Event=='SCAN'){
                    //填入数据库
                    $uin = $this->get_user($oid);
//                    dd($uin);
                    $tmp = [
                        'openid'=>$oid,
                        'openid_type'=>$res->EventKey,
                        'ticket'=>$res->Ticket,
                        'nickname'=>$uin['nickname'],
                        'sex'=>$uin['sex'],
                        'city'=>$uin['city']
                    ];
                    Tmp::insert($tmp);
                    //返回图文消息
                    echo "<xml><ToUserName><![CDATA[$oid]]></ToUserName><FromUserName><![CDATA[$gzhid]]></FromUserName><CreateTime>".time()."</CreateTime><MsgType><![CDATA[news]]></MsgType><ArticleCount>1</ArticleCount><Articles><item><Title><![CDATA[".$goodsinfo->goods_name."]]></Title><Description><![CDATA[iphone不好用了，能支持国产了！]]></Description><PicUrl><![CDATA[https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1556079981426&di=741f4c19088db58ea3339840587ba02f&imgtype=0&src=http%3A%2F%2Fwww.quaintfab.com%2FUploads%2Fimage%2F20160112%2F20160112032125_79518.jpg]]></PicUrl><Url><![CDATA[http://1809zhaokai.comcto.com/weixin/goods_detail/".$goodsinfo->id."]]></Url></item></Articles></xml>";
                }else{
                    $uin = $this->get_user($oid);
//                    dd($uin);
                    $key = explode('_',$res->EventKey);
                    $tmp = [
                        'openid'=>$oid,
                        'openid_type'=>$key[1],
                        'ticket'=>$res->Ticket,
                        'nickname'=>$uin['nickname'],
                        'sex'=>$uin['sex'],
                        'city'=>$uin['city']
                    ];
                    Tmp::insert($tmp);
                    echo "<xml><ToUserName><![CDATA[$oid]]></ToUserName><FromUserName><![CDATA[$gzhid]]></FromUserName><CreateTime>".time()."</CreateTime><MsgType><![CDATA[news]]></MsgType><ArticleCount>1</ArticleCount><Articles><item><Title><![CDATA[".$goodsinfo->goods_name."]]></Title><Description><![CDATA[iphone不好用了，能支持国产了！]]></Description><PicUrl><![CDATA[https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1556079981426&di=741f4c19088db58ea3339840587ba02f&imgtype=0&src=http%3A%2F%2Fwww.quaintfab.com%2FUploads%2Fimage%2F20160112%2F20160112032125_79518.jpg]]></PicUrl><Url><![CDATA[http://1809zhaokai.comcto.com/weixin/goods_detail/".$goodsinfo->id."]]></Url></item></Articles></xml>";
                }
            }else if($res->Event=='subscribe'){
                //查询用户存在不存在
                $u = Weixin::where('openid',$oid)->first();
                if($u){
                    echo "<xml><ToUserName><![CDATA[$oid]]></ToUserName><FromUserName><![CDATA[$gzhid]]></FromUserName><CreateTime>".time()."</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[欢迎回来！！".$u->nickname."]]></Content></xml>";
                }else{
                    $uinfo = $this->get_user($oid);
                    $da = [
                        'openid'=>$oid,
                        'nickname'=>$uinfo['nickname'],
                        'sex'=>$uinfo['sex'],
                        'headimgurl'=>$uinfo['headimgurl'],
                        'city'=>$uinfo['city'],
                        'province'=>$uinfo['province'],
                        'country'=>$uinfo['country']
                    ];
                    Weixin::insert($da);
                    echo "<xml><ToUserName><![CDATA[$oid]]></ToUserName><FromUserName><![CDATA[$gzhid]]></FromUserName><CreateTime>".time()."</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[欢迎关注！！]]></Content></xml>";
                }
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
    public function code()
    {
        //获取ticket
        $url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.get_wx_access();
//        echo $url;
        $data = [
            'action_name'=>'QR_LIMIT_SCENE',
            'action_info'=>[
                'scene'=>[
                    'scene_id'=>1
                ]
            ]
        ];
        $json_data = json_encode($data);
//        echo $json_data;
        $client = new Client();
        $respon = $client->request('POST',$url,[
            'body'=>$json_data
        ]);
        $arr = json_decode($respon->getBody(),true);
//        dd($arr);
        $ticket = UrlEncode($arr['ticket']);
//        echo $ticket;
        //通过ticket获取二维码
        $url2 = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".$ticket;
        echo $url2;
        $arr = file_get_contents($url2);
//        dd($arr);
    }
    //获取用户信息
    function get_user($openid)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.get_wx_access().'&openid='.$openid.'&lang=zh_CN';
        $userinfo = json_decode(file_get_contents($url),true);
        return $userinfo;
    }




    //周日任务
    public function display($id=0)
    {
        if(!$id){
            die('暂无商品信息');
        }
//        dd($_SERVER);
        $url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
//        echo $url;
        $data = Goods::where('id',$id)->first()->toArray();
        return view('weixin/aaa',['url'=>$url,'data'=>$data]);
    }
    //创建菜单
    public function create_m()
    {
        $url='https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.get_wx_access();
        $data = [
            "button"=>[
                    [
                        "type"=>"click",
                        "name"=>"最新xxxxx",
                        "key"=>"key_009",
                        "url"=>$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST']."/weixin/ggg",
                    ],
            ]
        ];
        $json_data = json_encode($data,JSON_UNESCAPED_UNICODE);
//        dd($json_data);
        $client = new Client();
        $respon = $client->request("POST",$url,[
            "body"=>$json_data
        ]);
        $arr = json_decode($respon->getBody(),true);
    }
    //授权
    public function ggg()
    {
      $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx9beeb571b5118194&redirect_uri=http%3A%2F%2F1809zhaokai.comcto.com%2Fweixin%2Fhuanying&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
    }
    public function huanying()
    {
        print_r($_GET);
    }
}
