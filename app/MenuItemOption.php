<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class MenuItemOption extends Model {

    protected $table ='menuitem_options';
    use SoftDeletes;

    protected $softDelete = true;


    public static function getMenuItemOptions($id) {

        $result = MenuItemOption::select('menuitem_options.*','m.item as item')
            ->join('menus as m','m.id','=','menuitem_options.option_item_id')
            ->where('parent_item_id',$id)->get();

        return $result;
    }

}
