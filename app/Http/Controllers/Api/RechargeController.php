<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RechargeController extends Controller
{
    public $tel_config= [
        1 => 0.95,
        2 => 0.93,
        3 => 0.93,
    ];
    public function telephone(Request $request)
    {
        $user = $request->user();
        $post = $request->post();
        $order = \DB::transaction(function () use ($user, $request){
            $order = new Order([
                'user_id'   =>  $user->id,
                'isp'   =>  $request->post('isp'),
                'telephone' =>  $request->post('telephone'),
                'price' =>  $request->post('amount')*$this->tel_config[$request->post('isp')],
                'recharge_type' =>  Order::RECHARGE_TELEPHONE,
                'recharge_json' => json_encode([
                    'amount'    =>  $request->post('amount'),
                    'isp'   =>  $request->post('isp'),
                    'telephone' =>  $request->post('telephone'),
                ])
            ]);
            $order->save();
            return $order;
        });
        return new OrderResource($order->fresh());
    }
    public function power(Request $request)
    {
        $user = $request->user();
        $post = $request->post();
        $order = \DB::transaction(function () use ($user, $request){
            $order = new Order([
                'user_id'   =>  $user->id,
                'isp'   =>  $request->post('isp'),
                'telephone' =>  $request->post('telephone'),
                'price' =>  $request->post('amount')*0.93,
                'recharge_type' =>  Order::RECHARGE_POWER,
                'recharge_json' => json_encode([
                    'amount'    =>  $request->post('amount'),
                    'area'   =>  $request->post('area'),
                    'cardno' =>  $request->post('cardno'),
                    'electricardtype' =>  $request->post('electricardtype'),
                    'electritype' =>  $request->post('electritype'),
                    'number' =>  $request->post('huhao'),
                    'telephone' =>  $request->post('telephone'),
                ])
            ]);
            $order->save();
            return $order;
        });
        return new OrderResource($order->fresh());
    }
}
