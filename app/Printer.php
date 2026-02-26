<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Printer extends Model {

    protected $table ='printers';

    use SoftDeletingTrait;

    protected $softDelete = true;

    protected $fillable = array
    (
        'printer_name',
        'outlet_id',
        'mac_address',
        'created_by',
        'updated_by'
    );


    public static function getOutletPrinters( $printer_arr,$outlet_id ) {

        $printers = array();$arr_count = 0;$avail_printers = array();
        if ( isset($printer_arr) && $printer_arr != '' ) {
            $pr_arr = json_decode($printer_arr);

            if ( isset($pr_arr->kot_printer) && $pr_arr->kot_printer != '' ) {
                $pr1 = Printer::where('id',$pr_arr->kot_printer)->first();
                if ( isset($pr1) && sizeof($pr1) > 0 ) {

                    $avail_printers[] = $pr1->id;
                    $printers[$arr_count]['id'] = $pr1->id;
                    $printers[$arr_count]['printer_name'] = $pr1->printer_name;
                    $printers[$arr_count]['printer_mfg'] = $pr1->printer_mfg;
                    $printers[$arr_count]['mac_address'] = $pr1->mac_address;
                    $printers[$arr_count]['ip_address'] = $pr1->printer_ip;
                    $printers[$arr_count]['printer_type'] = $pr1->printer_type;
                    $printers[$arr_count]['print_type'] = 'kot';
                    $printers[$arr_count]['created_at'] = $pr1->created_at;
                }
            }

            if ( isset($pr_arr->duplicate_kot_printer) && $pr_arr->duplicate_kot_printer != '' ) {
                $pr4 = Printer::where('id',$pr_arr->duplicate_kot_printer)->first();
                if ( isset($pr4) && sizeof($pr4) > 0 ) {
                    $arr_count++;
                    $avail_printers[] = $pr4->id;
                    $printers[$arr_count]['id'] = $pr4->id;
                    $printers[$arr_count]['printer_name'] = $pr4->printer_name;
                    $printers[$arr_count]['printer_mfg'] = $pr4->printer_mfg;
                    $printers[$arr_count]['mac_address'] = $pr4->mac_address;
                    $printers[$arr_count]['ip_address'] = $pr4->printer_ip;
                    $printers[$arr_count]['printer_type'] = $pr4->printer_type;
                    $printers[$arr_count]['print_type'] = 'duplicate_kot';
                    $printers[$arr_count]['created_at'] = $pr4->created_at;
                }
            }
            if ( isset($pr_arr->bill_printer) && $pr_arr->bill_printer != '' ) {
                $pr2 = Printer::where('id',$pr_arr->bill_printer)->first();
                if ( isset($pr1) && sizeof($pr1) > 0 ) {
                    $arr_count++;
                    $avail_printers[] = $pr2->id;
                    $printers[$arr_count]['id'] = $pr2->id;
                    $printers[$arr_count]['printer_name'] = $pr2->printer_name;
                    $printers[$arr_count]['printer_mfg'] = $pr2->printer_mfg;
                    $printers[$arr_count]['mac_address'] = $pr2->mac_address;
                    $printers[$arr_count]['ip_address'] = $pr2->printer_ip;
                    $printers[$arr_count]['printer_type'] = $pr2->printer_type;
                    $printers[$arr_count]['print_type'] = 'bill';
                    $printers[$arr_count]['created_at'] = $pr2->created_at;
                }
            }
            if ( isset($pr_arr->response_printer) && $pr_arr->response_printer != '' ) {
                $pr3 = Printer::where('id',$pr_arr->response_printer)->first();
                if ( isset($pr3) && sizeof($pr3) > 0 ) {
                    $arr_count++;
                    $avail_printers[] = $pr3->id;
                    $printers[$arr_count]['id'] = $pr3->id;
                    $printers[$arr_count]['printer_name'] = $pr3->printer_name;
                    $printers[$arr_count]['printer_mfg'] = $pr3->printer_mfg;
                    $printers[$arr_count]['mac_address'] = $pr3->mac_address;
                    $printers[$arr_count]['ip_address'] = $pr3->printer_ip;
                    $printers[$arr_count]['printer_type'] = $pr3->printer_type;
                    $printers[$arr_count]['print_type'] = 'response';
                    $printers[$arr_count]['created_at'] = $pr3->created_at;
                }
            }
        }

        $item_printers_obj = PrinterItemBind::where('outlet_id',$outlet_id)->get();

        if ( isset($item_printers_obj) && sizeof($item_printers_obj) > 0 ) {

            foreach ( $item_printers_obj as $itm_printer ) {

                if( !in_array( $itm_printer->printer_id,$avail_printers) ) {



                    $itm_prnt = Printer::where('id',$itm_printer->printer_id)->first();

                    if ( isset($itm_prnt) && sizeof($itm_prnt) > 0 ) {

                        $arr_count++;
                        $avail_printers[] = $itm_printer->printer_id;

                        $printers[$arr_count]['id'] = $itm_prnt->id;
                        $printers[$arr_count]['printer_name'] = $itm_prnt->printer_name;
                        $printers[$arr_count]['printer_mfg'] = $itm_prnt->printer_mfg;
                        $printers[$arr_count]['mac_address'] = $itm_prnt->mac_address;
                        $printers[$arr_count]['ip_address'] = $itm_prnt->printer_ip;
                        $printers[$arr_count]['printer_type'] = $itm_prnt->printer_type;
                        $printers[$arr_count]['print_type'] = 'kot';
                        $printers[$arr_count]['created_at'] = $itm_prnt->created_at;
                    }

                }

            }
        }

        return $printers;

    }


}
