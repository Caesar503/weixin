<?php

namespace App\Http\Controllers;

use App\Model\Cart;
use App\Model\Goods;
use App\Model\Order;
use App\Model\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
class OrderController extends Controller
{
    public function index()
    {
//        echo Order::gengrateOrderSn(Auth::id());
//        echo(Session::getId());
        //计算总金额
        $goods = Cart::where(['uid'=>Auth::id(),'session_id'=>Session::getId(),'is_status'=>1])->get()->toArray();
//        dd($goods);
        $amount = 0;
        $am = array_column($goods,'goods_price');
        foreach($am as $k=>$v){
            $amount +=$v;
        }



        //生成订单
        $orderinfo =[
            'order_sn' => Order::gengrateOrderSn(Auth::id()),
            'order_amount'=> $amount,
            'uid' => Auth::id(),
            'session_id'=>Session::getId()
        ];
        $a = Order::insertGetId($orderinfo);



        //加入订单详情表
        foreach($goods as $k1=>$v1){
            $orderdetail = [
                'oid'=>$a,
                'goods_id'=>$v1['goods_id'],
                'goods_name'=>$v1['goods_name'],
                'goods_price'=>$v1['num']*$v1['goods_price'],
                'uid'=>Auth::id()
            ];
            $res = OrderDetail::insert($orderdetail);
        }


        //清除购物车
        $r = Cart::where(['uid'=>Auth::id(),'session_id'=>Session::getId(),'is_status'=>1])->update(['is_status'=>2]);

//        dd($r);
        if($a&&$res&&$r){
            header("Refresh:2;url=/order/list");
            die('提交订单成功,跳转至订单列表展示');
        }else{
            header("Refresh:2;url=/cart");
            die('提交订单失败');
        }
    }
    public function list()
    {
       $res =  Order::OrderBy('id','desc')->where(['uid'=>Auth::id(),'session_id'=>Session::getId()])->get();
//        dd($res);
        return view('order.list',['res'=>$res]);
    }
}
