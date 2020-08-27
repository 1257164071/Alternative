<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Admin;
use Faker\Generator as Faker;

$factory->define(Admin::class, function (Faker $faker) {
    return [
        'username' => 'admin',
        'password' => bcrypt(123456),
        'name'  =>  'Administrator',
    ];
});
