<?php

namespace Tests\Feature\Admin\Permission;

use App\Models\Admin;
use App\Models\AuthGroup;
use App\Models\Roles;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Lauthz\Facades\Enforcer;
use Tests\AdminTestCase;
use Tests\TestCase;

class RoleTest extends AdminTestCase
{
    use RefreshDatabase;

    /** @test */
    public function cannot_view_role_list()
    {
        $admin = create(Admin::class);
        $auth = $this->signJwt($admin);
        $this->expectException('Lauthz\Exceptions\UnauthorizedException');
        $this->json('GET', '/api/admin/role', [], $auth)->assertStatus(422);
    }

    /** @test */
    public function can_view_role_list()
    {

        $auth = $this->authorization( '/api/admin/role', 'GET');
        $role = create(Roles::class, ['name' => 'admin', 'guard' => 'admin']);

        $authGroups = create(AuthGroup::class,[], 2);
        Enforcer::guard('admin')->addPermissionForUser($role->role, $authGroups->get(0)->rule, $authGroups->get(0)->action);

        $result = $this->json('GET', '/api/admin/role', [], $auth);
        $result->assertStatus(200);
        $result->assertJsonFragment([
            'name' => 'admin',
            'auth_group_ids' => [$authGroups->get(0)->id]
        ]);
    }

    /** @test */
    public function can_get_self_all_auth_group_list()
    {
        $admin = create(Admin::class);
        $auth = $this->signJwt($admin);
        $data = [
            ['id' => 1, 'name' => '主页', 'type' => 0, 'rule' => 'manage', 'action' => 'GET', 'parent_id' => 0, 'guard' => 'admin'],
            ['id' => 9, 'name' => '个人信息', 'type' => 1, 'rule' => '/api/admin/me', 'action' => 'GET', 'parent_id' => 1, 'guard' => 'admin'],
            ['id' => 10, 'name' => '分配权限', 'type' => 1, 'rule' => '/api/admin/auth-group-tree', 'action' => 'GET', 'parent_id' => 2, 'guard' => 'admin'],
        ];
        foreach ($data as $item) {
            Enforcer::guard('admin')->addPolicy('admin', $item['rule'], $item['action']);
        }
        AuthGroup::insert($data);
        Enforcer::guard('admin')->addRoleForUser($admin->getAuthIdentifier(), 'admin');
        $this->assertCount(count($data), Enforcer::guard('admin')->GetImplicitPermissionsForUser($admin->getAuthIdentifier()));
        $result = $this->json('GET', '/api/admin/auth-group-tree', [], $auth);
        $result->assertStatus(200);
        $resultJson = collect($data)->map(function ($item, $key){
            return collect($item)->forget('type')->forget('parent_id');
        });
        $result->assertJsonFragment($resultJson->get(0)->toArray());
        $result->assertJsonFragment($resultJson->get(1)->toArray());
    }

    /** @test */
    public function can_create_new_role()
    {
        $auth = $this->authorization('/api/admin/role', 'POST');
        $role = make(Roles::class);

        $data = [
            ['id' => 1, 'name' => '主页', 'type' => 0, 'rule' => 'manage', 'action' => 'GET', 'parent_id' => 0, 'guard' => 'admin'],
            ['id' => 9, 'name' => '个人信息', 'type' => 1, 'rule' => '/api/admin/me', 'action' => 'GET', 'parent_id' => 1, 'guard' => 'admin'],
            ['id' => 10, 'name' => '分配权限', 'type' => 1, 'rule' => '/api/admin/auth-group-tree', 'action' => 'GET', 'parent_id' => 2, 'guard' => 'admin'],
        ];
        AuthGroup::insert($data);

        $ids = AuthGroup::all()->pluck('id');
        $role = $role->toArray();
        $role['bind_auth_group_ids'] = $ids;

        $this->json('POST', '/api/admin/role', $role, $auth)->assertStatus(201);
        $this->assertCount(1, Roles::all());
        $role = Roles::first();
        $this->assertCount(count($data), Enforcer::guard($role->guard)->getPermissionsForUser($role->role));
    }

    /** @test */
    public function can_edit_role()
    {
        $auth = $this->authorization('/api/admin/role/*', 'PUT');

        $role = create(Roles::class);
        $authGroup = create(AuthGroup::class, [], 2);
        Enforcer::guard($role->guard)->addPolicy($role->role, $authGroup->get(0)->rule, $authGroup->get(0)->action);

        $newRole = $role->toArray();

        $newRole['name'] = 'tefsdklf';
        $newRole['remark'] = 'fdsfsd';
        $newRole['bind_auth_group_ids'] = [$authGroup->get(1)->id];
        $result = $this->json('PUT' ,'/api/admin/role/'.$role->id, $newRole, $auth);
        $result->assertStatus(200);

        $this->assertEquals($newRole['name'], $role->refresh()->name);
        $this->assertEquals($newRole['remark'], $role->refresh()->remark);

        $this->assertTrue(Enforcer::guard($role->guard)->hasPermissionForUser($role->refresh()->role, $authGroup->get(1)->rule, $authGroup->get(1)->action));
        $this->assertFalse(Enforcer::guard($role->guard)->hasPermissionForUser($role->refresh()->role, $authGroup->get(0)->rule, $authGroup->get(0)->action));
    }


}
