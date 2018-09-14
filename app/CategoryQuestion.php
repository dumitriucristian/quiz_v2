<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryQuestion extends Model
{

    public function addCategory($questionId, $categoryId){

        $question = \App\Question::find($questionId);
        $question->categories()->attach($categoryId);
    }

}
