<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = ['question_id', 'body', 'correct'];

    public function question(){
        $this->belongsTo(\App\Question::class);
    }


    /*display option values*/
    public function getSelectedValueAttribute(){

        return  ($this->correct == 0) ? 0 : 1;


    }

    public function getSelectedTextAttribute(){

        return  ($this->correct == 0) ? 'Incorrect':  'Correct' ;
    }

    public function getUnselectedValueAttribute(){

        return  ($this->correct == 0) ? 1 : 0;


    }

    public function getUnselectedTextAttribute(){

        return  ($this->correct == 0) ? 'Correct':  'Incorrect' ;
    }



}
