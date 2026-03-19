<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpenseCategory extends Model {

    use SoftDeletes;
    protected $table = 'expense_categories';
    protected $softDelete = true;

    protected $fillable = array
    (
        'name'
    );

    public static function getExpenseCategory($user_id) {

        $owner= Owner::where('id',$user_id )->select('created_by')->first();

        if( isset($owner) && $owner->created_by != "" ) {
            $menu_owner =$owner->created_by;
        }else{
            $menu_owner = $user_id;
        }

        return ExpenseCategory::where('created_by',$menu_owner)->get();

    }

}