<?php

namespace App\Admin\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Goods;
use App\Model\Weixin;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Support\Str;
use App\Model\Wxtext;
class ObController extends Controller
{
//    public function add()
//    {
//       return view('sucai.add');
//    }
    public function  add()
    {
        $url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token=".get_wx_access()."&type=image";
        $res = json_decode(file_get_contents($url),true);
        print_r($res);
    }
}
