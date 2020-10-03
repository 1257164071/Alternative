<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Roles;
use Faker\Generator as Faker;

$factory->define(Roles::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'guard'=>'admin',
        'remark' => $faker->text,
    ];
});
