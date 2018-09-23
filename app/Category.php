<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Category extends Model
{
    protected $fillable = array('name');

    public function questions()
    {
        return $this->hasMany('App\Question');
    }

    public function scopeCategoryTree($query)
    {

        $tree = [];
        $items =  Category::all();


        foreach($items as $item) {

             $pid = $item->parent_category;
             $id = $item->id;
             $name = $item->name;

             if(isset($tree[$pid])){
                 $tree[$pid]["childrens"][] = array("id"=>$id, "name" => $name, "pid" => $pid);
             }else{
                 $tree[$id] = array("id"=>$id, "name" => $name, "pid" => $pid);
             }
        }

        return  collect($tree)->toJson();
    }
}
