<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('results', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_quiz_id')->unique();
            $table->integer('user_id');
            $table->integer('quiz_id');
            $table->integer('nr_of_questions');
            $table->integer('nr_of_questions_answered');
            $table->integer('nr_of_correct_answers');
            $table->integer('nr_of_incorrect_answers');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('results');
    }
}
