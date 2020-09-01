<?php

namespace Tests\Feature\Permission;

use App\Models\Admin;
use App\Models\AuthGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\AdminTestCase;

class AuthGroupTest extends AdminTestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_may_not_view_auth_group()
    {
        $this->withExceptionHandling();
        $this->json('GET', '/api/admin/auth_group')->assertStatus(401);
    }


    /** @test */
    public function signed_in_user_can_view_auth_group()
    {
        $auth = $this->signJwt(create(Admin::class));
        factory(AuthGroup::class,40)->create();
        $result = $this->json('GET', '/api/admin/auth_group', [], $auth);
        $this->assertCount(15, $result['data']);
        $this->assertEquals(40 ,$result['meta']['total']);
    }

}
