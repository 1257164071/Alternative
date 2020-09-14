<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Admin;
use Faker\Generator as Faker;

$factory->define(Admin::class, function (Faker $faker) {
    return [
        'username' => $faker->name,
        'password' => 123456,
        'name'  =>  'Administrator',
        'role_id'  => function (){
            return factory(\App\Models\Role::class)->create();
        }
    ];
});

