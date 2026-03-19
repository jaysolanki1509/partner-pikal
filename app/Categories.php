<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Categories extends Model {


    protected $table = 'categories';

    protected $fillable = array(
        'title',
        'display_order',
    );

//    public static function getIngredientsByRecipeId($recipeDetails_id)
//    {
//        $ingredients = Ingredients::where('recipeDetails_id',$recipeDetails_id)->get();
//        return $ingredients;
//    }
}