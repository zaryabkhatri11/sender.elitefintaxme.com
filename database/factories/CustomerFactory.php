<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Customer;
use Faker\Generator as Faker;

$factory->define(Customer::class, function (Faker $faker) {

    return [
        'email' => $faker->word,
        'phone' => $faker->word,
        'owner_name' => $faker->word,
        'entity' => $faker->word,
        'owner_address' => $faker->text,
        'subject_mark' => $faker->word,
        'case_number' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
