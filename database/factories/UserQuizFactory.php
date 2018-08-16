<?php

use Faker\Generator as Faker;

$factory->define(App\UserQuiz::class, function (Faker $faker) {
    return [
       'quiz_id' => 1,
       'user_id'=> Auth::user()->id

    ];
});
