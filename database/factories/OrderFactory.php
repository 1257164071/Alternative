<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Order;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'user_id' => factory(\App\Models\Category::class)->create()->id,
        'recharge_type' => 1,
        'recharge_status' => \App\Models\Order::RECHARGE_STATUS_PENDING,
        'recharge_json' => json_encode(['telephone'=>18276518762],true),
        'telephone' => $faker->phoneNumber,
        'product_id' => 1,
        'price' => 100,
        'remark' => '',
        'paid_at' => '',
        'payment_no' => '',
        'refund_status' => \App\Models\Order::REFUND_STATUS_PENDING,
        'refund_no' => '',
//        'status' => ,

    ];
});
