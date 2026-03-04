<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Merchant;
use Faker\Generator as Faker;

$factory->define(Merchant::class, function (Faker $faker) {

    return [
        'payment_account_id' => $faker->word,
        'name' => $faker->word,
        'email' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
