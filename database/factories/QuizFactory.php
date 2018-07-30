<?php

use Faker\Generator as Faker;
use App\Quiz;

$factory->define(Quiz::class, function (Faker $faker) {
    return [
        'title' => $faker->title,
        'description' => $faker->text
    ];
});
/*
$factory->create(Quiz::class, 3)
    ->each(function(App\Quiz, $quiz)){
        factory('App\Question'::class, rand(0,100))
        ->create(['quiz_id' => $quiz->id]);
});

 */
