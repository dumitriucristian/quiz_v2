<?php

use Illuminate\Database\Seeder;

class CustomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/quiz.json");

        $data        = json_decode($json);
        $quiz        = $this->quiz($data);

        //create quiz
        $title       = $this->title($quiz);
        $description = $this->description($quiz);
        $questions   = $this->questions($quiz);

        foreach ($questions as $question) {
            //create question
            $questionText   = $question->questionText;

            //create category
            $category       = $question->category;
            $categoryName   = $category->name;
            $categoryParent = $category->parent;
            $answers        = $category->answers;

            foreach($answers as $answer)
            {
                //create answer
                $text   = $answer->text;
                $corect = $answer->corect;

                //create answer set

            }

        }

    }



    private function quiz($data)
    {
        return $data->quiz;
    }

    private function title($quiz)
    {
        return $quiz->title;
    }

    private function description($quiz)
    {
        return $quiz->description;
    }

    private function questions($quiz)
    {
        return $quiz->questions;
    }
}
