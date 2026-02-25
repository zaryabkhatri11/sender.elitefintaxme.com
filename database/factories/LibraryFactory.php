<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Library;
use Faker\Generator as Faker;

$factory->define(Library::class, function (Faker $faker) {

    return [
        'category_id' => $faker->word,
        'name' => $faker->word,
        'description' => $faker->text,
        'pdf' => $faker->word,
        'image' => $faker->word,
        'points' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
