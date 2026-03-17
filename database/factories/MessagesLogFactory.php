<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\MessagesLog;
use Faker\Generator as Faker;

$factory->define(MessagesLog::class, function (Faker $faker) {

    return [
        'user_id' => $faker->word,
        'customer_id' => $faker->word,
        'from_num' => $faker->word,
        'to_num' => $faker->word,
        'body' => $faker->text,
        'direction' => $faker->word,
        'message_sid' => $faker->word,
        'status' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
