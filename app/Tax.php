<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class Tax extends Model {

    protected $table = 'taxes';

    protected $fillable = array
    (
        'name',
        'created_by',
        'updated_by',
        'outlet_id',
        'tax_percent'

    );

    public static function calcTaxes($amount, $tax_name){

        $outlet_id = Session::get('outlet_session');
        $outlet = Outlet::find($outlet_id);
        $tax_total = 0;

        if(isset($outlet) && sizeof($outlet)>0){
            $all_taxes = json_decode($outlet->taxes);
            $selected_tax = array();
            if(isset($all_taxes) && sizeof($all_taxes)>0){
                foreach ($all_taxes as $slab=>$taxes){
                    if($slab == $tax_name){
                        $selected_tax = $taxes;
                    }
                }
            }

            if(isset($selected_tax) && sizeof($selected_tax)>0){
                foreach ($selected_tax as $tax){
                    $tax_total += ($tax->taxparc*$amount)/100;
                }
            }
        }
        return $tax_total;
    }

}
