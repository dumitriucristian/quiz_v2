<?php

namespace App;

use Illuminate\Database\Eloquent\Model;




class TagQuestion extends Model
{
    public function appendTags($questionId, $tagId)
    {

        $question = \App\Question::find($questionId);
         $question->tags()->attach($tagId);

    }

    public function removeTag($questionId, $tagId)
    {
        $question = \App\Question::find($questionId);
        $question->tags()->detach($tagId);
    }


}
