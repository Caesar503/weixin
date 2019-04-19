<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $table ='p_order';
    public $timestamps = false;
    /*
     * 生成订单号
     * */
    public static function gengrateOrderSn($p){
        $order_sn = '1809a_'.date('ymd').'_';
        $str = time().$p.rand(111,999).Str::random(16);
        $order_sn .=substr(md5($str),3,16);
        return $order_sn;
    }
}
