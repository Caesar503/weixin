<?php

namespace App\Admin\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Support\Str;
use Encore\Admin\Layout\Content;
use App\Model\Wxtext;
use App\Model\SC;
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
        if (request()->hasFile('sss') && request()->file('sss')->isValid()) {
            $photo = request()->file('sss');
            $extension = $photo->extension();
            //文件名称s
            $path = time() . 'test' . Str::random(8) . '.' . $extension;
            $store_result = $photo->storeAs('image', $path);
        }
        $url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token=".get_wx_access()."&type=image";
        $client = new Client();
        $response = $client->request('POST',$url,[
            'multipart' => [
                [
                    'name' => 'media',
                    'contents' =>fopen('../storage/app/image/'.$path,'r'),
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
        return $content
            ->header('群发')
            ->description('用户列表')
            ->body(view('sucai.msend',['openid'=>$a_openid]));
    }
}
