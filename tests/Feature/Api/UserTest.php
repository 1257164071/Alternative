<?php

namespace Tests\Feature\Api;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\UserTestCase;
use Lauthz\Facades\Enforcer;

class UserTest extends UserTestCase
{
    use RefreshDatabase;
    /** @test */
    public function user_test()
    {
        $user = factory(User::class)->create();
        $token = \Auth::guard('user')->login($user);
//        factory(Admin::class)->create();

        $this->json('GET', '/api/recharge/me', [
        ],['Authorization'=>'Bearer '.$token])->assertStatus(201);
    }

    /** @test */
    public function wechart_login()
    {
        $this->json('POST', '/api/recharge/socials/wechat/authorizations',[
            'code'  =>  '1',
            'access_token'  =>  '12'
        ])->assertOk();
    }



}
