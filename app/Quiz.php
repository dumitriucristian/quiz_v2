<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Quiz extends Model
{



    protected $fillable = ['title', 'description'];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public static function quizExist($data)
    {
        return ( (!$data) || ($data->count() == 0) ) ? false : true;
    }





}
