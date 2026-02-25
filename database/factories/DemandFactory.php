<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Demand;
use Faker\Generator as Faker;

$factory->define(Demand::class, function (Faker $faker) {

    return [
        'name' => $faker->word,
        'points' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
