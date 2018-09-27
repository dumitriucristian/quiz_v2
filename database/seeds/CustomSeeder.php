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
        $title       = $this->title($quiz);
        $description = $this->description($quiz);
        $questions   = $this->questions($quiz);

        dd($questions);

        foreach ($questions as $question) {
            dd($question->questionText);
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
