<?php

use Illuminate\Database\Seeder;
use App\Category;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = new Category();
        $category->name = 'Language Reference';
        $category->save();

        $category = new Category();
        $category->name = 'Types';
        $category->parent_category =1;
        $category->save();

        $category = new Category();
        $category->name = 'Variables';
        $category->parent_category = 1;
        $category->save();
    }
}
