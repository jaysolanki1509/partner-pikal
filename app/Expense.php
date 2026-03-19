<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Expense extends Model {

    use SoftDeletes;
    protected $table = 'expense';
    protected $softDelete = true;

    protected $fillable = array
    (
        'name',
        'expense_for',
        'created_by',
        'updated_by',
        'expense_by',
        'expense_to',
        'amount',
        'description',
        'verify',
        'expense_date'
    );

    public static function getStatus(){
        $logged_in_user = Auth::user();
        $outlet_id = Session::get('outlet_session');

        $outlet = Outlet::find($outlet_id);
        if($logged_in_user->id == $outlet->authorised_users) {
            return array("1" => "Entered", "2" => "Verified", "3" => "Paid", "4" => "Canceled");
        }else{
            return array("1" => "Entered");
        }

    }
}
