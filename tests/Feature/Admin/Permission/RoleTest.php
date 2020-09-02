<?php

namespace Tests\Feature\Admin\Permission;

use App\Models\Admin;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_may_not_view_roles()
    {
        $this->withExceptionHandling();
        $this->json('GET', '/api/admin/role')->assertStatus(401);
    }

    /** @test */
    public function signed_in_user_can_view_roles()
    {
        $auth = $this->signJwt(create(Admin::class));
        factory(Role::class, 40)->create();
        $result = $this->json('GET', '/api/admin/role', [], $auth);
        $result->assertStatus(200);
        $this->assertCount(15, $result['data']);
        $this->assertEquals(40, $result['meta']['total']);
    }

    /** @test */
    public function guests_may_not_create_role()
    {
        $this->withExceptionHandling();
        $this->json('POST', '/api/admin/role')->assertStatus(401);
    }

    /** @test */
    public function signed_in_user_can_create_new_role()
    {
        $auth = $this->signJwt(create(Admin::class));
        $role = make(Role::class);
        $this->assertCount(0, Role::all());
        $result = $this->json('POST', '/api/admin/role', $role->toArray(), $auth);
        $result->assertStatus(201);
        $this->assertCount(1, Role::all());
    }

    /** @test */
    public function bind_role_auth_group()
    {

    }
}
