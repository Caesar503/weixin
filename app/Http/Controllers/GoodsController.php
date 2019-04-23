<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Goods;
use Illuminate\Support\Facades\Redis;
class GoodsController extends Controller
{
    //浏览历史
    public function history($id=0){
        if(!$id){
            die('无商品');
        }
        $goodsinfo = Goods::where('id',$id)->first();
        if(!$goodsinfo){
            die('无此商品');
        }
        //浏览量
        $key = 'tmp:ss:abc:'.$id;
        $val = Redis::get($key);
        if(!$val){
            Redis::set($key,1);
            $view_count = 1;
        }else{
            $view_count = Redis::Incr($key);
        }
        //浏览量
        $k = 'cat:history:count';
        Redis::zadd($k,$view_count,$id);
        //浏览历史
        $kk = 'history:view';
        Redis::lpushx($kk,$id);
        $arr = Redis::lrange($kk,0,-1);
        $arr = array_unique($arr);
//        dump($arr);
        foreach($arr as $q=>$w){
            $history_info[] =Goods::where('id',$w)->first()->toArray();
        }
//        dump($history_info);
        return view('goods.index',['goodsinfo'=>$goodsinfo,'view_count'=>$view_count,'history'=>$history_info]);
    }
    //浏览量
    public function getscore(){
        $k = 'cat:history:count';
//        $arr = Redis::zrangebyscore($k,0,100,['WITHSCORES'=>true]);
        $arr = Redis::zrevrangebyscore($k,100,0,['WITHSCORES'=>true]);
//        print_r($arr);
        $keys = array_keys($arr);
        foreach($keys as $k=>$v){
            $info[] = Goods::where('id',$v)->first()->toArray();
        };

        foreach($info as $key=>$val){
            echo $val['id'];
            foreach($arr as $a=>$b){
                if($val['id']==$a){
                    $info[$key]['view_count']=$b;
                }
            }
        }
//        dump($info);
        return view('goods.history',['info'=>$info]);
    }
}
