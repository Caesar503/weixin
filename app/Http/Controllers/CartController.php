<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Model\Goods;
use App\Model\Cart;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    //购物车列表展示页面
    public function cartlist()
    {
        $cartinfo = Cart::where(['is_status'=>1])->get()->toArray();
        return view('cart.list',['cartinfo'=>$cartinfo]);
    }
    //添加购物车
    public function addcart($id=0)
    {
        if($id == ''){
            header("Refresh:3;url=/");
            die('请至少选择一样商品,3秒后自动跳回');
        }
        $goods_info = Goods::where('id',$id)->first();
        $data = [
            'goods_id'=>$id,
            'goods_name'=>$goods_info->goods_name,
            'goods_price'=>$goods_info->goods_price,
            'uid'=>Auth::id(),
            'session_id'=>Session::getId()
        ];
        $cart_id = Cart::insertGetId($data);
        if($cart_id){
            header("Refresh:3;url=/cart");
            echo "添加购物车成功,自动跳到购物车";
        }else{
            header("Refresh:3;url=/");
            echo "添加购物车失败，请重新添加";
        }
    }
}
