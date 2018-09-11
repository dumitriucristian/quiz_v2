<?php

use Faker\Generator as Faker;
use App\Quiz;
use App\Question;

$factory->define( Question::class, function (Faker $faker) {
    return [
        'body' => $faker->word,
    ];
});
