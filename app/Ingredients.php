<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Ingredients extends Model {


    protected $table = 'ingredients';

    protected $fillable = array(
        'recipeDetails_id',
        'ing_item_id',
        'qty',
    );

    public static function getIngredientsByRecipeId($recipeDetails_id)
    {
        $ingredients = Ingredients::where('ingredients.recipeDetails_id',$recipeDetails_id)
            ->join('menus','menus.id','=','ingredients.ing_item_id')
            ->join('unit','unit.id','=','menus.unit_id')
            ->select('menus.item','ingredients.id','ingredients.recipeDetails_id','ingredients.ing_item_id','ingredients.qty','menus.unit_id','unit.name as unit_name')
            ->get();
        return $ingredients;
    }

    public static function getIngredientsId($recipeDetails_id)
    {
        $ingredients = Ingredients::where('ingredients.recipeDetails_id', $recipeDetails_id)
            ->join('menus', 'menus.id', '=', 'ingredients.ing_item_id')
            ->pluck('ing_item_id','qty');

        return $ingredients;
    }

}