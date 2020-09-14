<?php

namespace Tests\Feature\Admin\Permission;

use App\Models\Admin;
use App\Models\AuthGroup;
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
        $auth_group = create(AuthGroup::class, [], 4);
        $role->bind_auth_group_ids = $auth_group->pluck('id')->toArray();
        create(AuthGroup::class);
        $result = $this->json('POST', '/api/admin/role', $role->toArray(), $auth);
        $result->assertStatus(201);

        $this->assertCount(5, AuthGroup::all());
        $this->assertCount(1, Role::all());
        $this->assertCount(4, Role::first()->auth_groups);
    }

    /** @test */
    public function create_new_role_must_have_name()
    {
        $this->withExceptionHandling();
        $auth = $this->signJwt(create(Admin::class));
        $role = make(Role::class);
        $role->name = '';
        $this->json('POST', '/api/admin/role', $role->toArray(), $auth)->assertJsonValidationErrors('name');
    }

    /** @test */
    public function create_new_role_must_have_guard()
    {
        $this->withExceptionHandling();
        $auth = $this->signJwt(create(Admin::class));
        $role = make(Role::class);
        $role->guard = '';
        $this->json('POST', '/api/admin/role', $role->toArray(), $auth)->assertJsonValidationErrors('guard');
    }

    /** @test */
    public function bind_role_auth_group_must_have_auth_group()
    {
        $this->withExceptionHandling();
        $auth = $this->signJwt(create(Admin::class));
        $role = create(Role::class);
        $this->json('POST', '/api/admin/role/' . $role->id . '/auth-group', [], $auth)->assertJsonValidationErrors('auth_group_ids');
    }

    /** @test */
    public function bind_role_auth_group()
    {
        $auth = $this->signJwt(create(Admin::class));
        $role = create(Role::class);
        $list = factory(AuthGroup::class, 10)->create();

        $this->assertCount(0, $role->auth_groups->all());
        $data = [
            'auth_group_ids' => $list->pluck('id')->toArray(),
        ];
        $this->json('POST', '/api/admin/role/' . $role->id . '/auth-group', $data, $auth);
        $this->assertCount(10, $role->refresh()->auth_groups->all());
    }

    /** @test */
    public function get_role_auth_list()
    {
        $auth = $this->signJwt(create(Admin::class));
        $role = create(Role::class);
        $list = factory(AuthGroup::class, 2)->create();
        foreach ($list as $key => $val) {
            create(AuthGroup::class, [
                'parent_id' => $val['id'],
            ], 1);
        }
        $ids = AuthGroup::get()->pluck('id')->toArray();
        $role->auth_groups()->attach($ids[1]);
        $this->json('GET', '/api/admin/role/' . $role->id . '/auth-group', [], $auth)
            ->assertStatus(200)
            ->assertJsonFragment(['id' => $ids[1]])
            ->assertJsonMissing(['id' => $ids[2]]);
    }

    /** @test */
    public function can_edit_role_list()
    {
        $auth = $this->signJwt(create(Admin::class));
        $role = create(Role::class);
        $authgroup = create(AuthGroup::class);

        $role->name = '123456fds';
        $role->bind_auth_group_ids = [$authgroup->id];
        $result = $this->json('PUT', '/api/admin/role/' . $role->id, $role->toArray(), $auth);
        $result->assertStatus(200);
        $role = Role::first();
        $this->assertEquals($role->name, '123456fds');
        $this->assertSame($role->auth_group_ids, [$authgroup->id]);
    }

    /** @test */
    public function admin_can_delete_role()
    {
        $auth = $this->signJwt(create(Admin::class));
        $role = create(Role::class);
        $this->assertCount(1, Role::all());
        $result = $this->json('DELETE', '/api/admin/role/' . $role->id, [], $auth);
        $result->assertStatus(204);
        $this->assertCount(0, Role::all());
    }


}
