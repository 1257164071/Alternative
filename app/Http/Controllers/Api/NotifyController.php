<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Services\MonkeyService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotifyController extends Controller
{
    //
    public function recharege(Request $request){
        $out_trade_num = $request->get('out_trade_num');
        $order = Order::query()->where(['no'=>$out_trade_num])->first();
        if ($order == ''){
            echo 'fail';
            return false;
        }
        switch ($request->get('state')){
            case 1:
                $order->recharge_status = Order::RECHARGE_STATUS_RECEIVED;
                $order->notify_order_json = json_encode($request->all());
                $order->save();
                echo 'success';
                return true;
        }
        echo 'fail';
        return false;
    }

    public function wechatNotify()
    {
        // 校验回调参数是否正确
        $data  = app('wechat_pay')->verify();
        // 找到对应的订单
        $order = Order::where('no', $data->out_trade_no)->first();
        // 订单不存在则告知微信支付
        if (!$order) {
            return 'fail';
        }
        // 订单已支付
        if ($order->paid_at) {
            // 告知微信支付此订单已处理
            return app('wechat_pay')->success();
        }

        // 将订单标记为已支付
        $order->update([
            'paid_at'        => Carbon::now(),
            'payment_no'     => $data->transaction_id,
        ]);

        return app('wechat_pay')->success();
    }
}
