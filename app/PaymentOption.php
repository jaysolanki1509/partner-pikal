<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PaymentOption extends Model {

    protected $table ='payment_options';
    use SoftDeletingTrait;

    protected $softDelete = true;

    protected $fillable = array
    (
        'name',
        'created_by',
        'updated_by',

    );

    #TODO: get payment option
    public static function getOutletPaymentOption($outlet_id) {

        $pay_opt_arr = array();

        $outlet_obj = Outlet::find($outlet_id);
        if ( isset($outlet_obj) && sizeof($outlet_obj) > 0 ) {

            $pay_opt = json_decode($outlet_obj->payment_options,true);

            if (isset($pay_opt) && sizeof($pay_opt) > 0 ) {
                foreach($pay_opt as $key=>$opt ) {

                    $pay_mode_arr = array();
                    $p_mode = PaymentOption::find($key);

                    if ( isset($p_mode) && sizeof($p_mode) > 0 ) {

                        $pay_mode_arr['mode_id'] = $p_mode->id;
                        $pay_mode_arr['mode_name'] = $p_mode->name;
                        $pay_mode_arr['source_id'] = 0;
                        $pay_mode_arr['source_name'] = '';

                        array_push($pay_opt_arr,$pay_mode_arr);

                        if ( is_array($opt)) {

                            //fix repeated single mode issue
                            if ( isset($opt) && $opt[0] == "") {
                                continue;
                            }

                            foreach ($opt as $op) {

                                $pay_mode_arr['mode_id'] = $p_mode->id;
                                $pay_mode_arr['mode_name'] = $p_mode->name;

                                $source = Sources::find($op);
                                if (isset($source) && sizeof($source) > 0) {
                                    $pay_mode_arr['source_id'] = $source->id;
                                    $pay_mode_arr['source_name'] = $source->name;
                                } else {
                                    $pay_mode_arr['source_id'] = 0;
                                    $pay_mode_arr['source_name'] = '';
                                }
                                array_push($pay_opt_arr,$pay_mode_arr);
                            }

                        }
                    }

                }
            }
        }

        //if payment option not set for outlet
        if ( sizeof($pay_opt_arr) == 0 ) {

            $pay_obj = PaymentOption::where('name','Cash')->first();
            if ( isset($pay_obj) && sizeof($pay_obj) > 0 ) {
                $pay_mode_arr['mode_id'] = $pay_obj->id;
                $pay_mode_arr['mode_name'] = $pay_obj->name;
                $pay_mode_arr['source_id'] = 0;
                $pay_mode_arr['source_name'] = '';

                array_push($pay_opt_arr,$pay_mode_arr);
            }

        }

        $unpaid_arr = array();
        $unpaid_arr['mode_id'] = 0;
        $unpaid_arr['mode_name'] = 'UnPaid';
        $unpaid_arr['source_id'] = 0;
        $unpaid_arr['source_name'] = '';
        array_push($pay_opt_arr,$unpaid_arr);

        return $pay_opt_arr;
    }

    public static function getPaymentOptionById($p_id){

        $payment_option = PaymentOption::find($p_id);

        if(isset($payment_option) && sizeof($payment_option)>0){
            return $payment_option->name;
        }else{
            return "UnPaid";
        }

    }


}
