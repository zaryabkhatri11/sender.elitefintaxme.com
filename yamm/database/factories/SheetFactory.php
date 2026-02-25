<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Sheet;
use Faker\Generator as Faker;

$factory->define(Sheet::class, function (Faker $faker) {

    return [
        'link' => $faker->word,
        'status' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
