<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use http\Client\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\AdminTestCase;
use Tymon\JWTAuth\JWTAuth;

class AdminTest extends AdminTestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_can_not_get_self_info()
    {
        $this->withExceptionHandling();
        $this->singIn();
        $this->json('GET', '/api/admin/admin')->assertStatus(401);
    }

    /** @test */
    public function can_get_self_info()
    {
        $user = ['username' => 'fdsfds'];
        $admin = factory(Admin::class)->create($user);
        $auth = $this->signJwt($admin);
// JWTAuth::guard('api')->parseToken()->authenticate()
        $this->json('GET', '/api/admin/admin', [], $auth)->assertStatus(200)->assertJsonFragment($user);
    }
}
