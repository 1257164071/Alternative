<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\AuthGroup;
use Faker\Generator as Faker;

$factory->define(AuthGroup::class, function (Faker $faker) {

    return array(
        [
            'id'    =>  1,
            'name' => '权限列表',
            'type' => AuthGroup::TYPE_MENU,
            'rule' => 'auth_group.index',
            'pid'  => 0,
            'guard'=> 'admin',
        ],
        [
            'id'    =>  2,
            'name' => '角色管理',
            'type' => AuthGroup::TYPE_MENU,
            'rule' => 'role.manager',
            'pid'  => 0,
            'guard'=> 'admin',
        ],
        [
            'id'    =>  3,
            'name' => '角色列表',
            'type' => AuthGroup::TYPE_MENU,
            'rule' => 'role.manager',
            'pid'  => 2,
            'guard'=> 'admin',
        ],
        [
            'id'    =>  4,
            'name' => '新增角色',
            'type' => AuthGroup::TYPE_OPERATE,
            'rule' => 'role.store',
            'pid'  => 3,
            'guard'=> 'admin',
        ],
        [
            'id'    =>  5,
            'name' => '更新角色',
            'type' => AuthGroup::TYPE_OPERATE,
            'rule' => 'role.patch',
            'pid'  => 3,
            'guard'=> 'admin',
        ],

        );

});
