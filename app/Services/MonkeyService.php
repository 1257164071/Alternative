<?php
namespace App\Services;

use App\Models\Order;

class MonkeyService
{
    public function __construct()
    {
        $this->apikey = env('MONKEY_KEY');
        $this->baseUrl = env('MONKEY_BASE_URL');
    }

    protected $apikey = '';
    protected $baseUrl = '';
    protected $userId = 169;
    protected $notifyUrl = 'http://47.105.64.110/api/aaaa';

    protected $urlList = [
        'userinfo'  =>  'index/user',   //查询用户信息
        'typecate'  =>  'index/typecate',   //获取产品类型和产品分类
        'check' =>  'index/check',
        'product'  =>  'index/product',
        'recharge'  =>  '/index/recharge',
    ];
    public function sign($param){
        ksort($param);
        //拼接签名串
        $sign_str = http_build_query($param) . '&apikey='.$this->apikey;
        //签名
        $sign = strtoupper(md5(urldecode($sign_str)));
        $param['sign'] = $sign;
        return $param;
    }

    public function test($order){

        $url = $this->baseUrl.$this->urlList['check'];

        $headers = array("Content-type:application/x-www-form-urlencoded");
        $param = [
            'userid'    =>  $this->userId,
            'out_trade_nums'    =>  '20220909050231766724'
        ];
        $params = $this->sign($param);
        $responseXml = $this->curlRequest($url, "POST", $headers, $params);
        dump($responseXml);die;

    }
    public function recharge($order){

        $url = $this->baseUrl.$this->urlList['recharge'];

        $headers = array("Content-type:application/x-www-form-urlencoded");

        if ($order->recharge_type == 'telephone'){
            $param = [
                'out_trade_num' =>  $order->no,
                'product_id'    =>  $order->product_id,
                'mobile'    =>  $order->telephone,
                'notify_url'    =>  $this->notifyUrl,
                'userid'    =>  $this->userId,
                'amount'    =>  $order->recharge,
                'price' =>  $order->price,
                'area'  =>  '',
                'ytype' =>  '',
                'id_card_no'    =>  '',
                'city'  =>  '',
            ];
        } elseif ($order->recharge_type == 'power'){
            $recharge_data = json_decode($order->recharge_json,true);
            $param = [
                'out_trade_num' =>  $order->no,
                'product_id'    =>  $order->product_id,
                'mobile'    =>  $recharge_data['number'],
                'notify_url'    =>  $this->notifyUrl,
                'userid'    =>  $this->userId,
                'amount'    =>  $order->recharge,
                'price' =>  $order->price,
                'area'  =>  mb_substr($recharge_data['area'],0,2),
                'ytype' =>  1,
                'id_card_no'    =>  $recharge_data['cardno'],
                'city'  =>  explode('/', $recharge_data['area'])[1],
            ];
        }
        //

        $params = $this->sign($param);
        $responseXml = $this->curlRequest($url, "POST", $headers, $params);
        $response = json_decode($responseXml,true);
//        $response = json_decode('{"errno":0,"errmsg":"提交成功","data":{"id":1673,"order_number":"HMA2209091673","mobile":"17669125149","product_id":34,"total_price":"92.00","create_time":1662699751,"guishu":"山东临沂","title":"联通100元话费","out_trade_num":"20220909050231766724"}}',true);

        if ($response['errno'] == 0){
            $order->order_number = $response['data']['order_number'];
            $order->recharge_order_json = $responseXml;
            $order->refund_status = Order::RECHARGE_STATUS_DELIVERED;
            $order->save();
//            dump($order->fresh()->toArray());die;
            return true;
        }

        return false;
    }

    public function curlRequest($url, $method, $headers, $params){
        if (is_array($params)) {
            $requestString = http_build_query($params);
        } else {
            $requestString = $params ? : '';
        }
        if (empty($headers)) {
            $headers = array('Content-type: text/json');
        } elseif (!is_array($headers)) {
            parse_str($headers,$headers);
        }
        // setting the curl parameters.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // turning off the server and peer verification(TrustManager Concept).
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        // setting the POST FIELD to curl
        switch ($method){
            case "GET" : curl_setopt($ch, CURLOPT_HTTPGET, 1);break;
            case "POST": curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $requestString);break;
            case "PUT" : curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $requestString);break;
            case "DELETE":  curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $requestString);break;
        }
        // getting response from server
        $response = curl_exec($ch);

        //close the connection
        curl_close($ch);
        //return the response
        if (stristr($response, 'HTTP 404') || $response == '') {
            return array('Error' => '请求错误');
        }
        return $response;
    }

}
