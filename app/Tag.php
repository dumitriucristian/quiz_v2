<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class Tag extends Model
{
    protected $fillable = array('name');


      public function questions()
    {
        return $this->belongsToMany('App\Question')->withTimestamps();
    }

    public function scopeQuestionTags($query, $id)
    {

        $questionTags = $this->questionTags($id);

        $missingQuestionTags  = $this->missingQuestionTags($questionTags);

        $result =
            array(
                "questionTags" => $questionTags,
                "missingQuestionTags" => $missingQuestionTags
            );

        $result = Collection::make($result);

        return $result;
    }

    private function questionTags($questionId){

          return DB::table('question_tag')
              ->Join($this->getTable(), 'tags.id' , '=', 'question_tag.tag_id')
              ->where('question_id', '=', $questionId)
              ->get();
    }

    private function missingQuestionTags($questionTags)
    {
        if($questionTags->count() > 0){
            $flatten = $questionTags->pluck('tag_id')->toArray();
            return \App\Tag::all()->whereNotIn('id',$flatten);

        }

        return \App\Tag::all();

    }




}

