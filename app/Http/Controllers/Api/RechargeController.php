<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\InvalidRequestException;
use App\Http\Resources\Api\OrderResource;
use App\Models\Card;
use App\Models\Order;
use App\Models\User;
use http\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
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
                'recharge' =>  $request->post('amount'),
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
                'recharge' =>  $request->post('amount'),
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


    public function opencard(Request $request){
        $card = Card::where(['no'=>$request->all('card_no')])->first();
        if ($card == ''){
            throw new InvalidRequestException('卡号错误', 400);
        }
        if ($card->status == 1){
            throw new InvalidRequestException('该卡已被使用', 400);
        }
        $user = \Auth::user();

        \DB::transaction(function () use ($user, $card){
            $card->user_id = $user->id;
            $card->status = 1;
            $card->use_date = date('Y-m-d H:i:s');
            $user->addRecharge($card->price);
            $card->save();
        });
    }

    public function getmoneyinfo(){
        $user = \Auth::user();
        $array = [
            'kaika' =>  \DB::table('balance_log')->where(['status'=>1,'user_id'=>$user->id])->sum('price'),
            'youhui'    =>  \DB::table('balance_log')->where(['status'=>2, 'user_id'=>$user->id])->sum('price'),
            'chongzhi'  =>  \DB::table('orders')->where(['user_id'=>$user->id,'refund_status'=>Order::RECHARGE_STATUS_RECEIVED])->sum('recharge'),
        ];
        return json_encode($array);
    }

    public function openlist(Request $request){
//        $statuses = \Auth::user()->balance_log()
        $statuses = \Auth::user()->use_card()
            ->orderBy('use_date', 'desc')
            ->paginate(20);
        return json_encode(['data'=>$statuses]);
    }

    public function uselist(Request $request){
        $statuses = \Auth::user()->balance_log()
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return json_encode(['data'=>$statuses]);
    }

    public function card()
    {
        $num = 100;
        $price = 100.00;
        $list = [];
        $userid = 0;
        for ($i=0; $i<$num; $i++){
            $list[] = [
                'user_id' => $userid,
                'no'    =>  $this->makeCardPassword(),
                'price' =>  $price,
                'status'    =>  0,
                'created_at'    =>  date('Y-m-d H:i:s'),
                'updated_at'    =>  date('Y-m-d H:i:s'),
            ];
        }
        \DB::transaction(function () use ($list){
            \DB::table('card')->insert($list);
        });
        $res = \DB::table('card')->get();
        return json_encode($res);
    }
    public function downloadfile($data = array(),$filename = "unknown") {
        if (!empty($data)){
            foreach($data as $key=>$val){
                foreach ($val as $ck => $cv) {
                    $data[$key][$ck]=iconv("UTF-8", "GB2312", $cv);
                }
                $data[$key]=implode(" ", $data[$key]);
            }
            $txt = implode("\n",$data);
        }
        $response = new StreamedResponse();
        $response->setCallBack(function () use($txt) {
            echo $txt;
        });
        $disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'logs.txt');
        $response->headers->set('Content-Disposition', $disposition);

        return $response;

        exit();
    }
    public function  makeCardPassword() {
        $code = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $rand = $code[rand(0,25)]
            .strtoupper(dechex(date('m')))
            .date('d').substr(time(),-5)
            .substr(microtime(),2,5)
            .sprintf('%02d',rand(0,99));
        for(
            $a = md5( $rand, true ),
            $s = '0123456789ABCDEFGHIJKLMNOPQRSTUV',
            $d = '',
            $f = 0;
            $f < 8;
            $g = ord( $a[ $f ] ),
            $d .= $s[ ( $g ^ ord( $a[ $f + 8 ] ) ) - $g & 0x1F ],
            $f++
        );
        return  $d;
    }



}
