<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\AuthGroup;
use Faker\Generator as Faker;

$factory->define(AuthGroup::class, function (Faker $faker) {

    return [
        'name' => $faker->slug,
        'type' => AuthGroup::TYPE_MENU,
        'rule' => $faker->slug.$faker->name,
        'parent_id' => 0,
        'guard' => 'admin',
        'action' => $faker->randomKey(['POST','GET']),
    ];

});
