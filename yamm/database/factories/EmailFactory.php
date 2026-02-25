<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Email;
use Faker\Generator as Faker;

$factory->define(Email::class, function (Faker $faker) {

    return [
        'emails' => $faker->word,
        'status' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
