<?php

namespace App;

use Illuminate\Database\Eloquent\Model;




class TagQuestion extends Model
{
    public function appendTags($questionId, $tags){

        $question = \App\Question::find($questionId);

        foreach($tags as $tag){
            $question->tags()->attach($tag);
        }


    }
}
