<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Order;
class CrontabController extends Controller
{
    public function delorder()
    {
        $time = time();
        $res = Order::get()->toArray();
        foreach($res as $k=>$v){
            // 下单时间超过半小时                 未支付
            if($time - $v['pay_time'] >1800&&$v['pay_status']==1){
                //删除订单
                Order::where('id',$v['id'])->update(['is_status'=>2]);
            }
        }
    }
}
