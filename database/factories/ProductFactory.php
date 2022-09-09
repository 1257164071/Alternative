<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'title' => $faker->title,
        'description' => $faker->text,
        'image' => $faker->imageUrl(),
        'on_sale' => $faker->boolean,
        'rating' => $faker->randomFloat(10,0),
        'price' => $faker->randomFloat(1000,1),
        'category_id' => factory(\App\Models\Category::class)->create()->id,
    ];
});
