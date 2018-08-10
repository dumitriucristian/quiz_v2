<?php

use Faker\Generator as Faker;


$factory->define(\App\UserQuiz::class, function (Faker $faker) {
    return [
        'quiz_id'=>1,
        'user_id'=>Auth::user()->id,
        'user_quiz_id'=>1,
        'question_id'=>1,
        'answer_set' =>'10',
        'is_valid_answer_set'=>true

    ];
});
