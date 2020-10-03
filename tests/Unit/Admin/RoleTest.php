<?php

namespace Tests\Unit\Admin;

use App\Models\Admin;
use App\Models\AuthGroup;
use App\Models\Roles;
use App\Services\RoleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lauthz\Facades\Enforcer;
use Tests\AdminTestCase;

class RoleTest extends AdminTestCase
{
    use RefreshDatabase;


    /** @test */
    public function can_get_role_list_tree()
    {
        factory(AuthGroup::class, 5)->create()->each(function ($item){
            create(AuthGroup::class,[
                'parent_id' => $item->id,
                'type'  =>  rand(0,1)
            ],5);
        });
        $service = new RoleService();
        $result = $service->getRoleTree();
        $this->assertCount(5,$result);
        $this->assertCount(5, $result[0]['children']);
    }

    /** @test */
    public function get_admin_user_auth_group_ids()
    {
        $admin = create(Admin::class);
        $data = [
            ['id' => 1, 'name' => '主页', 'type' => 0, 'rule' => 'manage', 'action' => 'GET', 'parent_id' => 0, 'guard' => 'admin'],
            ['id' => 9, 'name' => '个人信息', 'type' => 1, 'rule' => '/api/admin/me', 'action' => 'GET', 'parent_id' => 1, 'guard' => 'admin'],
            ['id' => 10, 'name' => '分配权限', 'type' => 1, 'rule' => '/api/admin/auth-group-tree', 'action' => 'GET', 'parent_id' => 2, 'guard' => 'admin'],
        ];
        foreach ($data as $item) {
            Enforcer::guard('admin')->addPolicy('admin', $item['rule'], $item['action']);
        }
        AuthGroup::insert($data);
        $service = new RoleService();
        $this->assertCount(0, $service->getRoleAuthGroupIds($admin->getAuthIdentifier()));
        Enforcer::addRoleForUser($admin->getAuthIdentifier(), 'admin');
        $this->assertCount(count($data), $service->getRoleAuthGroupIds($admin->getAuthIdentifier()));
    }

    /** @test */
    public function can_know_auth_groups_for_role()
    {
        $roles = create(Roles::class);

        $data = [
            ['id' => 1, 'name' => '主页', 'type' => 0, 'rule' => 'manage', 'action' => 'GET', 'parent_id' => 0, 'guard' => 'admin'],
            ['id' => 9, 'name' => '个人信息', 'type' => 1, 'rule' => '/api/admin/me', 'action' => 'GET', 'parent_id' => 1, 'guard' => 'admin'],
            ['id' => 10, 'name' => '分配权限', 'type' => 1, 'rule' => '/api/admin/auth-group-tree', 'action' => 'GET', 'parent_id' => 2, 'guard' => 'admin'],
        ];
        foreach ($data as $item) {
            Enforcer::guard('admin')->addPolicy($roles->role, $item['rule'], $item['action']);
        }
        AuthGroup::insert($data);
        $this->assertCount(3, $roles->AuthGroupIds);
    }



}
