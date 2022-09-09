<?php
namespace Tests\Unit\Api;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\OrderTest;
use Tests\TestCase;
use App\Services\MonkeyService;
class RechargeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function recharge_test(){


        $user = factory(User::class)->create();
        $token = \Auth::guard('user')->login($user);
        $list = [
            [
                'name'  =>  '移动100元',
                'price' =>  94,
                'type'  =>   1,
                'amount'    =>  100,
                'type_id'   =>  32,
                'cate_name' =>  '三网话F-一般1-24小时内到账！',
                'isp'   =>  '1',
            ],
            [
                'name'  =>  '移动200元',
                'price' =>  188.00,
                'type'  =>   1,
                'amount'    =>  200,
                'type_id'   =>  33,
                'cate_name' =>  '三网话F-一般1-24小时内到账！',
                'isp'   =>  '1',
            ],
            [
                'name'  =>  '联通100元',
                'price' =>  92,
                'type'  =>   1,
                'amount'    =>  100,
                'type_id'   =>  34,
                'cate_name' =>  '三网话F-一般1-24小时内到账！',
                'isp'   =>  '3',
            ],
            [
                'name'  =>  '联通200元',
                'price' =>  184,
                'type'  =>   1,
                'amount'    =>  200,
                'type_id'   =>  35,
                'cate_name' =>  '三网话F-一般1-24小时内到账！',
                'isp'   =>  '3',
            ],
            [
                'name'  =>  '电信100元',
                'price' =>  92,
                'type'  =>   1,
                'amount'    =>  100,
                'type_id'   =>  36,
                'cate_name' =>  '三网话F-一般1-24小时内到账！',
                'isp'   =>  2,

            ],
            [
                'name'  =>  '电信200元',
                'price' =>  184,
                'type'  =>   1,
                'amount'    =>  200,
                'type_id'   =>  37,
                'cate_name' =>  '三网话F-一般1-24小时内到账！',
                'isp'   =>  2,

            ],
            [
                'name'  =>  '国网100元',
                'price' =>  93,
                'type'  =>   2,
                'amount'    =>  100,
                'type_id'   =>  16,
                'cate_name' =>  '国网专区（注：四川 北京 上海禁止下单）',
                'isp'   =>  0,

            ],
            [
                'name'  =>  '国网200元',
                'price' =>  186,
                'type'  =>   2,
                'amount'    =>  200,
                'type_id'   =>  17,
                'cate_name' =>  '国网专区（注：四川 北京 上海禁止下单）',
                'isp'   =>  0,

            ],
            [
                'name'  =>  '国网300元',
                'price' =>  279,
                'type'  =>   2,
                'amount'    =>  300,
                'type_id'   =>  18,
                'cate_name' =>  '国网专区（注：四川 北京 上海禁止下单）',
                'isp'   =>  0,

            ],

            [
                'name'  =>  '云闪付100元',
                'price' =>  95,
                'type'  =>   2,
                'amount'    =>  100,
                'type_id'   =>  63,
                'cate_name' =>  '国网专区（注：四川 北京 上海禁止下单）',
                'isp'   =>  0,

            ],
            [
                'name'  =>  '云闪付200元',
                'price' =>  190,
                'type'  =>   2,
                'amount'    =>  200,
                'type_id'   =>  64,
                'cate_name' =>  '国网专区（注：四川 北京 上海禁止下单）',
                'isp'   =>  0,

            ],
            [
                'name'  =>  '云闪付300元',
                'price' =>  285,
                'type'  =>   2,
                'amount'    =>  300,
                'type_id'   =>  66,
                'cate_name' =>  '国网专区（注：四川 北京 上海禁止下单）',
                'isp'   =>  0,

            ],
            [
                'name'  =>  '南网100元',
                'price' =>  92,
                'type'  =>   2,
                'amount'    =>  100,
                'type_id'   =>  1,
                'cate_name' =>  '国网专区（注：四川 北京 上海禁止下单）',
                'isp'   =>  0,

            ],
            [
                'name'  =>  '南网200元',
                'price' =>  184,
                'type'  =>   2,
                'amount'    =>  200,
                'type_id'   =>  2,
                'cate_name' =>  '国网专区（注：四川 北京 上海禁止下单）',
                'isp'   =>  0,

            ],
            [
                'name'  =>  '南网300元',
                'price' =>  460,
                'type'  =>   2,
                'amount'    =>  500,
                'type_id'   =>  5,
                'cate_name' =>  '国网专区（注：四川 北京 上海禁止下单）',
                'isp'   =>  0,

            ],
        ];

        \DB::transaction(function () use ($list){
            \App\Models\Typecate::insert($list);
        });
        $response = $this->json('POST', '/api/recharge/power', [
            'amount'=>100,
            'area'   =>  '山东省/临沂市/兰山区',
            'cardno' =>  123456,
            'electricardtype' =>  2,
            'electritype' =>  1,
            'number' =>  1025786375,
            'recharge_type' =>  'power',
            'telephone' =>  18265197620,
            'type_id' =>  1,
        ],['Authorization'=>'Bearer '.$token])->assertStatus(200);

        $order = Order::first();

        $service = new MonkeyService();
        $result  = $service->test($order);

        $this->assertCount(5,$result);
    }
    /** @test */

    public function recharge_telephone_test(){

        $user = factory(User::class)->create();
        $token = \Auth::guard('user')->login($user);
        $list = [
            [
                'name'  =>  '移动100元',
                'price' =>  94,
                'type'  =>   1,
                'amount'    =>  100,
                'type_id'   =>  32,
                'cate_name' =>  '三网话F-一般1-24小时内到账！',
                'isp'   =>  '1',
            ],
            [
                'name'  =>  '移动200元',
                'price' =>  188.00,
                'type'  =>   1,
                'amount'    =>  200,
                'type_id'   =>  33,
                'cate_name' =>  '三网话F-一般1-24小时内到账！',
                'isp'   =>  '1',
            ],
            [
                'name'  =>  '联通100元',
                'price' =>  92,
                'type'  =>   1,
                'amount'    =>  100,
                'type_id'   =>  34,
                'cate_name' =>  '三网话F-一般1-24小时内到账！',
                'isp'   =>  '3',
            ],
            [
                'name'  =>  '联通200元',
                'price' =>  184,
                'type'  =>   1,
                'amount'    =>  200,
                'type_id'   =>  35,
                'cate_name' =>  '三网话F-一般1-24小时内到账！',
                'isp'   =>  '3',
            ],
            [
                'name'  =>  '电信100元',
                'price' =>  92,
                'type'  =>   1,
                'amount'    =>  100,
                'type_id'   =>  36,
                'cate_name' =>  '三网话F-一般1-24小时内到账！',
                'isp'   =>  2,

            ],
            [
                'name'  =>  '电信200元',
                'price' =>  184,
                'type'  =>   1,
                'amount'    =>  200,
                'type_id'   =>  37,
                'cate_name' =>  '三网话F-一般1-24小时内到账！',
                'isp'   =>  2,

            ],
            [
                'name'  =>  '国网100元',
                'price' =>  93,
                'type'  =>   2,
                'amount'    =>  100,
                'type_id'   =>  16,
                'cate_name' =>  '国网专区（注：四川 北京 上海禁止下单）',
                'isp'   =>  0,

            ],
            [
                'name'  =>  '国网200元',
                'price' =>  186,
                'type'  =>   2,
                'amount'    =>  200,
                'type_id'   =>  17,
                'cate_name' =>  '国网专区（注：四川 北京 上海禁止下单）',
                'isp'   =>  0,

            ],
            [
                'name'  =>  '国网300元',
                'price' =>  279,
                'type'  =>   2,
                'amount'    =>  300,
                'type_id'   =>  18,
                'cate_name' =>  '国网专区（注：四川 北京 上海禁止下单）',
                'isp'   =>  0,

            ],

            [
                'name'  =>  '云闪付100元',
                'price' =>  95,
                'type'  =>   2,
                'amount'    =>  100,
                'type_id'   =>  63,
                'cate_name' =>  '国网专区（注：四川 北京 上海禁止下单）',
                'isp'   =>  0,

            ],
            [
                'name'  =>  '云闪付200元',
                'price' =>  190,
                'type'  =>   2,
                'amount'    =>  200,
                'type_id'   =>  64,
                'cate_name' =>  '国网专区（注：四川 北京 上海禁止下单）',
                'isp'   =>  0,

            ],
            [
                'name'  =>  '云闪付300元',
                'price' =>  285,
                'type'  =>   2,
                'amount'    =>  300,
                'type_id'   =>  66,
                'cate_name' =>  '国网专区（注：四川 北京 上海禁止下单）',
                'isp'   =>  0,

            ],
            [
                'name'  =>  '南网100元',
                'price' =>  92,
                'type'  =>   2,
                'amount'    =>  100,
                'type_id'   =>  1,
                'cate_name' =>  '国网专区（注：四川 北京 上海禁止下单）',
                'isp'   =>  0,

            ],
            [
                'name'  =>  '南网200元',
                'price' =>  184,
                'type'  =>   2,
                'amount'    =>  200,
                'type_id'   =>  2,
                'cate_name' =>  '国网专区（注：四川 北京 上海禁止下单）',
                'isp'   =>  0,

            ],
            [
                'name'  =>  '南网300元',
                'price' =>  460,
                'type'  =>   2,
                'amount'    =>  500,
                'type_id'   =>  5,
                'cate_name' =>  '国网专区（注：四川 北京 上海禁止下单）',
                'isp'   =>  0,

            ],
        ];

        \DB::transaction(function () use ($list){
            \App\Models\Typecate::insert($list);
        });
        $response = $this->json('POST', '/api/recharge/telephone', [
            'amount'=>100,
            'isp'   =>  3,
            'telephone' =>  17669125149,
        ],['Authorization'=>'Bearer '.$token])->assertStatus(200);

        $order = Order::first();

        $service = new MonkeyService();
        $result  = $service->test($order);
    }


}
