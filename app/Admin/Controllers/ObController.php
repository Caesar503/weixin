<?php

namespace App\Admin\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp;
use Illuminate\Support\Str;
use Encore\Admin\Layout\Content;
use App\Model\Wxtext;
use App\Model\SC;
use GuzzleHttp\Psr7\Uri;
class ObController extends Controller
{
    public function add(Content $content)
    {
        return $content
            ->header('添加临时素材')
            ->description('添加临时素材')
            ->body(view('sucai.add'));
    }
    public function  adddo(Content $content)
    {
//        dd($_FILES);
        $type=$_FILES['sss']['type'];
        $type=explode("/",$type);
//        dd($type);
        if($type['0']=="image"){
            $type_file="image";
        }else if($type['0']=="video"){
            $type_file="video";
        }else if($type['1']=="mp3"||$type['1']=="amr"){
            $type_file="voice";
        }else{
            $type_file="thumb";
        }
        //上传文件 保存文件到服务器
        if (request()->hasFile('sss') && request()->file('sss')->isValid()) {
            $photo = request()->file('sss');
            $extension = $photo->getClientOriginalExtension();
            //文件名称s
            $path = time() . 'test' . Str::random(8) . '.' . $extension;
            $store_result = $photo->storeAs($type_file, $path);
        }
        //发送到素材库
        $url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token=".get_wx_access()."&type=".$type_file;
        $client = new Client();
        $response = $client->request('POST',$url,[
            'multipart' => [
                [
                    'name' => 'media',
                    'contents' =>fopen('../storage/app/'.$type_file.'/'.$path,'r'),
                ]
            ]
        ]);
        $json = $response->getBody();
        $data = json_decode($json,true);


        //入库
        SC::insert($data);

        return $content
            ->header('ok')
            ->description(' ')
            ->body('已添加成功');
    }
    public function more_send(Content $content)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=".get_wx_access()."&next_openid=";
        $openid = json_decode(file_get_contents($url),true);
        $a_openid = $openid['data']['openid'];
        //从素材库获取素材
        $sc_data = SC::get()->toArray();

        return $content
            ->header('群发')
            ->description('用户列表')
            ->body(view('sucai.msend',['openid'=>$a_openid,'sc_data'=>$sc_data]));
    }
    public function send_do(){
//         dd($_POST);
        //类型
        $type = $_POST['send_type'];
        //群发内容
        $content = $_POST['send_content'];
        //用户id组
        $oid = rtrim($_POST['openid'],',');
        $openid = explode(',',$oid);
        //拼接参数
        if($type=='text'){
            $data = [
                'touser'=>$openid,
                'msgtype'=>$type,
                "$type"=>[
                    'content'=>$content
                ]
            ];
        }else{
            $data = [
                'touser'=>$openid,
                'msgtype'=>$type,
                "$type"=>[
                    'media_id'=>$content
                ]
            ];
        }
        //
        $json_data = json_encode($data,JSON_UNESCAPED_UNICODE);

        //调用接口
        $url = "https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=".get_wx_access();
        //发送
        $client = new Client();
        $respon = $client->request('POST',$url,[
            'body'=>$json_data
        ]);
        if($respon){
            echo 1;
        }else{
            echo 2;
        }
    }
}
