<?php

use Illuminate\Database\Seeder;
use App\Models\AuthGroup;

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
                'parent_id' => 0,
                'guard' => 'admin',
            ],
            [
                'id' => 2,
                'name' => '角色管理',
                'type' => AuthGroup::TYPE_MENU,
                'rule' => 'role.manager',
                'parent_id' => 0,
                'guard' => 'admin',
            ],
            [
                'id' => 3,
                'name' => '角色列表',
                'type' => AuthGroup::TYPE_MENU,
                'rule' => 'role.manager.index',
                'parent_id' => 2,
                'guard' => 'admin',
            ],
            [
                'id' => 4,
                'name' => '新增角色',
                'type' => AuthGroup::TYPE_OPERATE,
                'rule' => 'role.store',
                'parent_id' => 3,
                'guard' => 'admin',
            ],
            [
                'id' => 5,
                'name' => '更新角色',
                'type' => AuthGroup::TYPE_OPERATE,
                'rule' => 'role.patch',
                'parent_id' => 3,
                'guard' => 'admin',
            ],

        );
        $data = [
            ['id' => 1, 'name' => '主页', 'type' => 0, 'rule' => 'manage', 'parent_id' => 0, 'guard' => 'admin'],
            ['id' => 2, 'name' => '权限管理', 'type' => 0, 'rule' => 'auth_group', 'parent_id' => 0, 'guard' => 'admin'],
            ['id' => 3, 'name' => '角色管理', 'type' => 0, 'rule' => 'role.manager', 'parent_id' => 2, 'guard' => 'admin'],
            ['id' => 4, 'name' => '角色列表', 'type' => 1, 'rule' => 'role.index', 'parent_id' => 3, 'guard' => 'admin'],
            ['id' => 5, 'name' => '新增角色', 'type' => 1, 'rule' => 'role.store', 'parent_id' => 3, 'guard' => 'admin'],
            ['id' => 6, 'name' => '更新角色', 'type' => 1, 'rule' => 'role.patch', 'parent_id' => 3, 'guard' => 'admin'],
            ['id' => 7, 'name' => '管理员管理', 'type' => 0, 'rule' => 'admin.manager', 'parent_id' => 2, 'guard' => 'admin'],
            ['id' => 8, 'name' => '管理员列表', 'type' => 1, 'rule' => 'admin.manager', 'parent_id' => 7, 'guard' => 'admin'],
        ];


        AuthGroup::insert($data);
    }
}
