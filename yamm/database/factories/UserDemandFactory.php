<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\UserDemand;
use Faker\Generator as Faker;

$factory->define(UserDemand::class, function (Faker $faker) {

    return [
        'user_id' => $faker->word,
        'demand_id' => $faker->word,
        'points' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
