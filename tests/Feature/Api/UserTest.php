<?php

namespace Tests\Feature\Api;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\UserTestCase;
use Lauthz\Facades\Enforcer;

class UserTest extends UserTestCase
{
    use RefreshDatabase;
    /** @test */
    public function user_test()
    {
        $this->json('GET', '/api/recharge/me', [
            'username' => '',
            'password' => '123456',
        ])->assertOk();
    }



}
