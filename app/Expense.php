<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use DateTime;

class Expense extends Model {

    use SoftDeletingTrait;
    protected $table = 'expense';
    protected $softDelete = true;

    /**
     * Laravel 5.1 upgrade: Controls the storage format for Eloquent date fields.
     * Previously done via getDateFormat() override; now use $dateFormat property.
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['expense_date', 'deleted_at'];

    /**
     * Laravel 5.1 upgrade: Prepare a date for array / JSON serialization.
     * In 5.1, the $dateFormat is also applied during serialization.
     * Override this method to keep JSON output format unchanged for API consumers.
     *
     * @param  \DateTime  $date
     * @return string
     */
    protected function serializeDate(DateTime $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

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
