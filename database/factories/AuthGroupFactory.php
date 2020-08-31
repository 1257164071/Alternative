<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\AuthGroup;
use Faker\Generator as Faker;

$factory->define(AuthGroup::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'type' => 0,
        'rule' => '/api/home/auth_group',
        'pid'  => 0,
        'guard'=> 'admin',
    ];
});
