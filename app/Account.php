<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Account extends Model {

    protected $table ='accounts';
    use SoftDeletes;

    protected $softDelete = true;

    protected $fillable = array
    (
        'name',
        'enable_inventory',
    );

    public static function canResetInvoice() {

        $account_id=Auth::user()->account_id;
        
        if(isset($account_id) && !empty($account_id)) {

            $account = Account::find($account_id);
            if(isset($account) && !empty($account)) {
                return $account->can_invoice_reset;
            } else {
                return 0;
            }
        } else {
             return 0;
        }

    }


}
