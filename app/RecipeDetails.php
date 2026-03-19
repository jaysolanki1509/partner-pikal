<?php namespace App;

use Aws\CloudFront\Exception\Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RecipeDetails extends Model {


    protected $table = 'recipeDetails';

    protected $fillable = array(
        'owner_id',
        'outlet_id',
        'menu_title_id',
        'menu_item_id',
        'referance',
    );

    public static function getRecipeDetailsbyOwnerid($owner_id){
        $recipeDetail = RecipeDetails::where('owner_id',$owner_id)
            ->join('menus','menus.id','=','recipeDetails.menu_item_id')
            ->join('unit as u','u.id','=','menus.unit_id')
            ->select('menus.item','recipeDetails.id','recipeDetails.owner_id','recipeDetails.outlet_id','recipeDetails.menu_item_id','recipeDetails.referance','menus.unit_id','u.name as unit_name')
            ->get();
        return $recipeDetail;
    }

    public static function getRecipeDetailsByOutletId($outlet_id){


        $recipeDetail = RecipeDetails::where('recipeDetails.outlet_id',$outlet_id)
            ->join('menus','menus.id','=','recipeDetails.menu_item_id')
            ->select('menus.item','recipeDetails.id','recipeDetails.owner_id','recipeDetails.outlet_id','recipeDetails.menu_item_id','recipeDetails.referance','recipeDetails.unit_id')->get();

        //print_r($recipeDetail);exit;
        return $recipeDetail;
    }

    public static function getRecipeDetailsById($id){
        $recipe = RecipeDetails::where('menu_item_id',$id)->first();
        return $recipe;
    }

    public static function getIngredientsArr($item_id)
    {
        $recipe_details = RecipeDetails::where('menu_item_id', $item_id)->first();
        $recipe_id = isset($recipe_details)?$recipe_details->id:null;
        $recipe_base_qty = isset($recipe_details)?$recipe_details->referance:1;

        $ingredients = Ingredients::where('ingredients.recipeDetails_id', $recipe_id)
            ->join('menus', 'menus.id', '=', 'ingredients.ing_item_id')
            ->select('ing_item_id','qty')->get();

        $result_arr = array();
        $i = 0;
        if(isset($ingredients) && isset($ingredients)) {
            foreach ($ingredients as $ingred_item) {
                $menu = Menu::find($ingred_item->ing_item_id);
                $result_arr[$i]['qty'] = round($ingred_item->qty/$recipe_base_qty,2);
                $result_arr[$i++]['price'] = isset($menu->buy_price)?$menu->buy_price:0;
            }
        }
        return $result_arr;
    }

}