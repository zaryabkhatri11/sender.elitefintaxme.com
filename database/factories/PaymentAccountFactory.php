<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\PaymentAccount;
use Faker\Generator as Faker;

$factory->define(PaymentAccount::class, function (Faker $faker) {

    return [
        'name' => $faker->word,
        'gateway' => $faker->word,
        'credentials' => $faker->word,
        'is_active' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
