<?php

use Faker\Generator as Faker;
use \App\Question;
use \App\Answer;

$factory->define(Answer::class, function (Faker $faker) {
   return[
       'question_id' => function() {
                 return factory(Question::class)->create()->id;
            },
       'body' => $faker->word,
       'correct' => rand(0,1)
       ];

});
