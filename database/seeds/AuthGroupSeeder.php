<?php

use Illuminate\Database\Seeder;
use App\Models\AuthGroup;
use Lauthz\Facades\Enforcer;

class AuthGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data = array(
            [
                'id' => 1,
                'name' => '权限列表',
                'type' => AuthGroup::TYPE_MENU,
                'rule' => 'auth_group.index',
                'action' => 'GET',
                'parent_id' => 0,
                'guard' => 'admin',
            ],
            [
                'id' => 2,
                'name' => '角色管理',
                'type' => AuthGroup::TYPE_MENU,
                'rule' => 'role.manager',
                'action' => 'GET',
                'parent_id' => 0,
                'guard' => 'admin',
            ],
            [
                'id' => 3,
                'name' => '角色列表',
                'type' => AuthGroup::TYPE_MENU,
                'rule' => 'role.manager.index',
                'action' => 'GET',
                'parent_id' => 2,
                'guard' => 'admin',
            ],
            [
                'id' => 4,
                'name' => '新增角色',
                'type' => AuthGroup::TYPE_OPERATE,
                'rule' => 'role.store',
                'action' => 'POST',
                'parent_id' => 3,
                'guard' => 'admin',
            ],
            [
                'id' => 5,
                'name' => '更新角色',
                'type' => AuthGroup::TYPE_OPERATE,
                'rule' => 'role.patch',
                'action' => 'PATCH',
                'parent_id' => 3,
                'guard' => 'admin',
            ],

        );
        $data = [
            ['id' => 1, 'name' => '主页', 'type' => 0, 'rule' => 'manage', 'action' => 'GET', 'parent_id' => 0, 'guard' => 'admin'],
            ['id' => 9, 'name' => '个人信息', 'type' => 1, 'rule' => '/api/admin/me', 'action' => 'GET', 'parent_id' => 1, 'guard' => 'admin'],
            ['id' => 2, 'name' => '权限管理', 'type' => 0, 'rule' => 'auth_group', 'action' => 'GET', 'parent_id' => 0, 'guard' => 'admin'],
            ['id' => 3, 'name' => '角色管理', 'type' => 0, 'rule' => 'role.manager', 'action' => 'GET', 'parent_id' => 2, 'guard' => 'admin'],
            ['id' => 10, 'name' => '分配权限列表', 'type' => 1, 'rule' => '/api/admin/auth-group-tree', 'action' => 'GET', 'parent_id' => 2, 'guard' => 'admin'],
            ['id' => 4, 'name' => '角色列表', 'type' => 1, 'rule' => '/api/admin/role', 'action' => 'GET', 'parent_id' => 3, 'guard' => 'admin'],
            ['id' => 5, 'name' => '新增角色', 'type' => 1, 'rule' => '/api/admin/role', 'action' => 'POST', 'parent_id' => 3, 'guard' => 'admin'],
            ['id' => 6, 'name' => '更新角色', 'type' => 1, 'rule' => '/api/admin/role', 'action' => 'PUT', 'parent_id' => 3, 'guard' => 'admin'],
            ['id' => 7, 'name' => '管理员管理', 'type' => 0, 'rule' => '/api/admin/admin', 'action' => 'GET', 'parent_id' => 2, 'guard' => 'admin'],
            ['id' => 8, 'name' => '管理员列表', 'type' => 1, 'rule' => '/api/admin/admin', 'action' => 'GET', 'parent_id' => 7, 'guard' => 'admin'],
        ];
        foreach ($data as $item) {
            Enforcer::guard('admin')->addPolicy('admin', $item['rule'], $item['action']);
        }
        AuthGroup::insert($data);
    }
}
