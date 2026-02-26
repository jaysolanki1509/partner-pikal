<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Account extends Model {

    protected $table ='accounts';
    use SoftDeletingTrait;

    protected $softDelete = true;

    protected $fillable = array
    (
        'name',
        'enable_inventory',
    );

    public static function canResetInvoice() {

        $account_id=Auth::user()->account_id;

        if(isset($account_id) && sizeof($account_id)>0) {

            $account = Account::find($account_id);

            if(isset($account) && sizeof($account)>0) {
                return $account->can_invoice_reset;
            } else {
                return 0;
            }
        } else {
             return 0;
        }

    }


}
