<?php

use Faker\Generator as Faker;

$factory->define(\App\UserAnswer::class, function (Faker $faker) {
    return [
        'user_answer'=>$faker->word
    ];
});
