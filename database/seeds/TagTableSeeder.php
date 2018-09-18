<?php

use Illuminate\Database\Seeder;
use App\Tag;

class TagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tag = new Tag();
        $tag->name = 'php';
        $tag->save();

        $tag = new Tag();
        $tag->name = 'javascript';
        $tag->save();

        $tag = new Tag();
        $tag->name = 'mysql';
        $tag->save();
    }
}
