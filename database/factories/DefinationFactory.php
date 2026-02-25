<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Defination;
use Faker\Generator as Faker;

$factory->define(Defination::class, function (Faker $faker) {

    return [
        'name' => $faker->word,
        'description' => $faker->text,
        'status' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
