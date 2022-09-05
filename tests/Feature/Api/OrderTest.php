<?php

namespace Tests\Feature\Api;

use App\Models\Admin;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\UserTestCase;
use Lauthz\Facades\Enforcer;

class OrderTest extends UserTestCase
{
    use RefreshDatabase;
    /** @test */
    public function user_test()
    {
        $product = factory(Order::class)->create();
        dump($product->toArray());
    }

    /** @test */
    public function telephone_test()
    {
        $user = factory(User::class)->create();
        $token = \Auth::guard('user')->login($user);
//        factory(Admin::class)->create();

        $response = $this->json('POST', '/api/recharge/telephone', [
            'amount'=>100,
            'isp'   =>  1,
            'telephone' =>  18165297620,
        ],['Authorization'=>'Bearer '.$token])->assertStatus(200);
        dump($response->dump()->json());

    }

    /** @test */
    public function card_test()
    {
        $user = factory(User::class)->create();
        $token = \Auth::guard('user')->login($user);
        $response = $this->json('GET', '/api/recharge/card', [
            'amount'=>100,
            'isp'   =>  1,
            'telephone' =>  18165297620,
        ],['Authorization'=>'Bearer '.$token])->assertStatus(200);

    }

}
