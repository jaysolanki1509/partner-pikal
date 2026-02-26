<?php namespace App\Jobs;
use App\Jobs\Command;
use App\DailyReportPdf;
use App\Http\Controllers\Api\v1\Apicontroller;
use App\Http\Controllers\ReportController;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Support\Facades\Log;
use App\order_details;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Support\Facades\DB;
use App\Outlet;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\OrderItem;
use Maatwebsite\Excel\Facades\Excel;
use TCPDF;
use Exception;

class ReportsMail extends Command {
    use InteractsWithQueue, SerializesModels;
    public function sendmails($job,$fields){
        //Log::info("Queue started.");
        $data = array();
        $from_date = new Carbon(date('Y-m-d').' 00:00:00');
        $to_date = new Carbon(date('Y-m-d').' 23:59:59');

        $outlets = Outlet::where('id', $fields['outlet_id'])->get();
        if( sizeof($outlets) > 0 ){
            foreach($outlets as $outlet){
                //Log::info($outlet->id." outlet id");
                $html='';
                $total_tax = 0;
                $total_discount = 0;
                $total_final = 0;
                $total_person_visit = 0;
                $total_subtotal = 0;
                $excel_data=array();

                $orders = order_details::join("order_items","order_items.order_id","=","orders.order_id")
                    /*->join("invoice_details as inv","inv.order_id","=","orders.order_id")*/
                    ->select('orders.*','order_items.item_quantity as Quantity','order_items.item_name as item_name'/*,"inv.total as inv_total","inv.discount as inv_discount","inv.sub_total as inv_sub_total","inv.round_off as inv_round_off"*/)
                    ->where('orders.table_start_date','>=',Carbon::today()->startOfDay())
                    ->where('orders.table_start_date','<=', Carbon::today()->endOfDay())
                    ->where('orders.outlet_id','=',$outlet->id)
                    ->where('orders.cancelorder', '!=', 1)
                    ->where('orders.invoice_no',"!=",'')
                    ->orderBy('orders.created_at', 'asc')
                    ->get();

                $itemlist=array();
                $itemlist_excel=array();
                $orders1=array();
                $n=1;
                if( isset($orders) && sizeof($orders) > 0 ){
                    foreach($orders as $order) {
                        if (array_key_exists($order->order_id,$itemlist)) {
                            if($order->Quantity>1){
                                $itemlist[$order->order_id] = $itemlist[$order->order_id] . ",<br />" .$order->Quantity." x ".strtoupper($order->item_name);
                                $itemlist_excel[$order->order_id] = $itemlist_excel[$order->order_id] . ",\n" .$order->Quantity." x ".strtoupper($order->item_name);
                            }else {
                                $itemlist[$order->order_id] = $itemlist[$order->order_id] . ",<br />" ."1 x ".strtoupper($order->item_name);
                                $itemlist_excel[$order->order_id] = $itemlist_excel[$order->order_id] . ",\n" ."1 x ".strtoupper($order->item_name);
                            }
                        } else {
                            if($order->Quantity>1){
                                $itemlist[$order->order_id] = $order->Quantity." x ".strtoupper($order->item_name);
                                $itemlist_excel[$order->order_id] = $order->Quantity." x ".strtoupper($order->item_name);
                            }else {
                                $itemlist[$order->order_id] = "1 x ".strtoupper($order->item_name);
                                $itemlist_excel[$order->order_id] = "1 x ".strtoupper($order->item_name);
                            }
                            array_push($orders1,$order);
                        }
                    }
                    $outlet_details=Outlet::Outletbyid2($outlet->id);

                    $html = '<label style="text-align: center"><b>'.$outlet_details->name.'</b> '.Carbon::today()->format('d-m-Y(D)').'</label>
            <br/><br/>
            <table class="table table-striped table-bordered table-hover" border="1">
                <thead>
                    <tr style="text-align: center; font-size: smaller;">
                        <th><b>Invoice </b> </th>
                        <th width="25px"><b>T# </b> </th>
                        <th width="25px"><b>P# </b> </th>
                        <th> <b> Order Type </b> </th>
                        <th width="100px"> <b> Item Name </b> </th>
                        <th style="text-align:center; padding:5px; "> <b> Sub Total </b> </th>
                        <th style="text-align:center; padding:5px; "> <b> Discount </b> </th>
                        <th style="text-align:center; padding:5px; "> <b> Tax </b> </th>
                        <th style="text-align:center; padding:5px; "> <b> Round Off </b> </th>
                        <th> <b> Amount </b> </th>
                    </tr>
                </thead>
                <tbody id="table_body">';
                    $n = 0;
                    foreach($orders1 as $ord){
                        $tax_total=0;
                        $json_tax=json_decode($ord->tax_type);
                        if(sizeof($json_tax)>0 && isset($json_tax))
                        {
                            foreach( $json_tax as $tx ){
                                if(gettype($tx) == 'string')
                                    $tx1 = json_decode($tx);
                                else
                                    $tx1 = $tx;
                                foreach( $tx1 as $key1=>$t){
                                    $tax_total += $t->calc_tax;
                                }
                            }
                        }

                        $total_tax += $tax_total;
                        $total_discount += $ord->discount_value;
                        $total_final += floatval($ord->totalprice);
                        $total_subtotal += floatval($ord->totalcost_afterdiscount);
                        $total_person_visit += $ord->person_no;

                        $excel_data[$n]['Invoice'] = $ord->invoice_no;
                        $excel_data[$n]['Table No.'] = $ord->table_no;
                        $excel_data[$n]['No. Of Person Visited'] = $ord->person_no;
                        $excel_data[$n]['Order Type'] = ReportController::get_order_type($ord->order_type);
                        $excel_data[$n]['Item List'] = ucfirst($itemlist_excel[$ord->order_id]);
                        $excel_data[$n]['Sub Total'] = number_format($ord->totalcost_afterdiscount,2);
                        $excel_data[$n]['Discount'] = number_format($ord->discount_value,2);
                        $excel_data[$n]['Total Tax Amount'] = number_format($tax_total,2);
                        $excel_data[$n]['Round Off'] = number_format($ord->round_off,2);
                        $excel_data[$n]['Gross Total'] = number_format($ord->totalprice,2);

                        $html .= '<tr style="text-align: center; font-size: smaller;">
                            <td> &nbsp; '. $ord->invoice_no .'</td>
                            <td width="25px"> '. $ord->table_no .' &nbsp; </td>
                            <td width="25px"> '. $ord->person_no .' &nbsp; </td>
                            <td> &nbsp; '. ReportController::get_order_type($ord->order_type) .' &nbsp; </td>
                            <td width="100px" style="text-align: left">'. ucfirst($itemlist[$ord->order_id]) .' &nbsp; </td>
                            <td style="text-align:right; padding-right:5px; "> &nbsp; '. number_format($ord->totalcost_afterdiscount,2) .' &nbsp; </td>
                            <td style="text-align:right; padding-right:5px; "> &nbsp; '. number_format($ord->discount_value,2) .' &nbsp; </td>
                            <td style="text-align:right; padding-right:5px; "> &nbsp; '. number_format($tax_total,2) .' &nbsp; </td>
                            <td style="text-align:right; padding:5px; "> &nbsp; '. number_format($ord->round_off,2) .' &nbsp; </td>
                            <td style="text-align:right; padding:5px; "> &nbsp; '. number_format($ord->totalprice,2) .' &nbsp; </td>
                        </tr>';
                        $n++;
                    }

                    $excel_data[$n]['Invoice'] = "";
                    $excel_data[$n]['Table No.'] = "Total ";
                    $excel_data[$n]['No. Of Person Visited'] = $total_person_visit;
                    $excel_data[$n]['Order Type'] = "";
                    $excel_data[$n]['Item List'] = "";
                    $excel_data[$n]['Sub Total'] = number_format($total_subtotal,2);
                    $excel_data[$n]['Discount'] = number_format($total_discount,2);
                    $excel_data[$n]['Total Tax Amount'] = number_format($total_tax,2);
                    $excel_data[$n]['Round Off'] = "";
                    $excel_data[$n]['Gross Total'] = number_format($total_final,2);

                    $html .= '<tr style="text-align:right; font-size: smaller;">
                            <td colspan="2"> Total &nbsp; </td>
                            <td> &nbsp; '. $total_person_visit .' &nbsp; </td>
                            <td colspan="2"> &nbsp;  &nbsp; </td>
                            <td> &nbsp; '. number_format($total_subtotal,2) .' &nbsp; </td>
                            <td> &nbsp; '. number_format($total_discount,2) .' &nbsp; </td>
                            <td> &nbsp; '. number_format($total_tax,2) .' &nbsp; </td>
                            <td> &nbsp; &nbsp; </td>
                            <td> &nbsp; '. number_format($total_final,2) .' &nbsp; </td>
                        </tr>';
                    $html .= '</tbody>
                    </table>';

                    $pdf = new TCPDF();

                    $pdf->setPrintHeader(false);
                    $pdf->setPrintFooter(false);
                    $pdf->SetTitle($outlet_details->name);
                    $pdf->SetPrintFooter(false);
                    $pdf->AddPage();
                    $pdf->writeHTML($html, true, false, true, false, '');
                    $parent_path=storage_path().'/daily_order_pdf/';
                    if(!is_dir($parent_path))
                        mkdir($parent_path, 0777);
                    $path = storage_path().'/daily_order_pdf/'.$outlet->id.'/';
                    if(!is_dir($path))
                        $path_created=mkdir($path, 0777);
                    chmod(storage_path().'/daily_order_pdf/',0777);
                    chmod($path,0777);
                    $filename = $path.'/Detail_Report_of_'.Carbon::today()->format('d-m-Y').'.pdf';
                    DailyReportPdf::savePdfData($outlet->id,'Detail_Report_of_'.Carbon::today()->format('d-m-Y').'.pdf',$filename,date('Y-m-d'));
                    $pdf->output($filename, 'F');

                    $excel_name = 'Detail_excel_Report_of_'.Carbon::today()->format('d-m-Y');

                    Excel::create($excel_name, function($excel) use($excel_data) {

                        $excel->sheet('Sheet1', function($sheet) use($excel_data) {

                            $sheet->cells('A1:J1', function ($cells){
                                $cells->setBackground('#E04833');
                                $cells->setFontColor('#ffffff');

                            });

                            $sheet->setOrientation('landscape');
                            $sheet->fromArray($excel_data);
                            //$sheet->setAutoSize(true);
                        });
                        $excel->getActiveSheet()->getStyle('E1:E'.$excel->getActiveSheet()->getHighestRow())
                            ->getAlignment()->setWrapText(true);
                        foreach($excel->getActiveSheet()->getRowDimensions() as $rd) {
                            $rd->setRowHeight(-1);
                        }
                    })->store('xls', $path);

                }

                $total_tax = 0.0;
                $data['outlet_name'] = $outlet->name;
                $data['start_date'] =$fields['start_date'];
                $data['close_date'] = $fields['end_date'];
                $data['total_hours'] =$fields['total_hours'];
                $data['start_time'] = $fields['start_time'];
                $data['end_time'] =$fields['end_time'];
                $data['remarks'] = $fields['remark'];

                $orders = order_details::where('orders.table_start_date','>=', (new Carbon($from_date))->startOfDay())
                                    ->where('orders.table_end_date','<=', (new Carbon($to_date))->endOfDay())
                                    ->where('orders.outlet_id','=',$outlet->id)
                                    ->where('orders.invoice_no',"!=",'')
                                    ->where('orders.cancelorder', '!=', 1);

                $orderscount = order_details::where('table_start_date','>=', (new Carbon($from_date))->startOfDay())
                    ->where('table_start_date','<=', (new Carbon($to_date))->endOfDay())->where('outlet_id','=',$outlet->id)->count();

                $data['total_sell'] = number_format($orders->sum('orders.totalcost_afterdiscount'),0);
                $data['gross_total'] = number_format($orders->sum('orders.totalprice'),0);
                $data['total_person'] = $orders->sum('orders.person_no');
                $data['lowest_order'] = number_format($orders->min('orders.totalprice'),0);
                $data['highest_order'] = number_format($orders->max('orders.totalprice'),0);

               // $data['total_orders'] = $orders->count();
                $data['total_orders'] = $orderscount;
                $data['average'] =  number_format($orders->avg('orders.totalprice'), 2);
                $data['total_discount'] = 0;
                $data['total_nc'] = 0;

                $Camount = preg_replace('/,/', '', $data['gross_total']);
                $Camount = preg_replace('/\s*/', '', $Camount);

                $data['total_byuser']=$fields['amount_byuser'];
                $data['total_diff']=$Camount-$fields['amount_byuser'];

                if( sizeof($order_arr = $orders->get()) > 0  ){

                    $discount = 0 ;$nc = 0;$t_cash = 0;$t_prepaid = 0;$t_person_visit = 0;
                    foreach( $order_arr as $or ) {

                        //get discount amount and non chargeable amount
                        $disc_amt = floatval($or->discount_value);
                        $st_amt = floatval($or->totalcost_afterdiscount);
                        if ( $disc_amt == '') {
                            $disc_amt = 0;
                        }
                        if ( $disc_amt == $st_amt ) {
                            $nc += $disc_amt;
                        } else {
                            $discount += $disc_amt;
                        }
                        //get total cash and prepaid amount
                        if ( strtolower($or->paid_type) == 'cod' ) {
                            $t_cash += $or->totalprice;
                        } else {
                            $t_prepaid += $or->totalprice;
                        }

                        $tax_total=0;
                        $json_tax=json_decode($or->tax_type);
                        if(sizeof($json_tax)>0 && isset($json_tax))
                        {
                            foreach( $json_tax as $tx ){
                                if(gettype($tx) == 'string')
                                    $tx1 = json_decode($tx);
                                else
                                    $tx1 = $tx;
                                foreach( $tx1 as $key1=>$t){
                                    $tax_total += $t->calc_tax;
                                }
                            }
                        }

                        $total_tax += $tax_total;
                        $t_person_visit += $or->person_no;

                    }

                    $data['total_discount'] = number_format($discount,0);
                    $data['total_nc'] = number_format($nc,0);
                    $data['total_cash'] = number_format($t_cash,0);
                    $data['total_prepaid'] = number_format($t_prepaid,0);


                   // Log::info("Total Orders ==> ".sizeof($orders));
                    $items = OrderItem::join("orders", "orders.order_id", "=", "order_items.order_id")
                        ->join('menus','menus.id','=','order_items.item_id')
                        ->select('order_items.id', "menus.item", DB::raw('ifnull(sum(order_items.item_quantity),0) as count'))
                        ->where('orders.table_start_date','>=', (new Carbon($from_date))->startOfDay())
                        ->where('orders.table_end_date','<=', (new Carbon($to_date))->endOfDay())
                        ->where('orders.outlet_id','=',$outlet->id)
                        ->where('orders.cancelorder', '!=', 1)
                        ->where('orders.invoice_no',"!=",'')
                        ->groupBy('order_items.item_name')
                        ->get();

                    $data['top_selling_item'] = "None";
                    $count = 0;
                    foreach ($items as $item) {
                        if ($item->count > $count) {
                            $count = $item->count;
                            $data['top_selling_item'] = ucfirst($item->item);
                        }
                    }

                    $cancel_order = order_details::leftJoin('order_cancellation_mapper as ocm','ocm.order_id','=','orders.order_id')
                                                ->leftJoin('owners as o','o.id','=','ocm.created_by')
                                                ->select('orders.*','ocm.reason as reason','o.user_name as user_name')
                                                ->where('orders.table_start_date','>=', (new Carbon($from_date))->startOfDay())
                                                ->where('orders.table_end_date','<=', (new Carbon($to_date))->endOfDay())
                                                ->where('orders.outlet_id','=',$outlet->id)
                                                ->where('orders.cancelorder', '=', 1);

                    $data['cancel_order']=$cancel_order->count();
                    $data['cancel_amount'] = number_format($cancel_order->sum('orders.totalprice'),0);
                    $data['cancel_order_arr'] = $cancel_order->get();

                    $data['subject'] = $outlet->name.' '.date('d-m-Y');
                    $emails = array();
                    if (isset($outlet->report_emails) && $outlet->report_emails != ''){
                        $emails = explode(',',$outlet->report_emails);
                    }

                    $emails1 = array("dev@savitriya.com");
                    //$allemail=array_merge($emails,$emails1);
                    $allemail = array();

                    if ( sizeof($allemail) > 0 ){
                        foreach($allemail as $email){
                            //try {
                                Mail::send('emails.dailysummaryreport', array('data' => $data), function ($message) use ($data, $email) {
                                    $message->from('we@pikal.io', 'Pikal');
                                    $message->to($email);
                                    $message->subject($data['subject']);
                                });
                               Log::info('Mail Sent to : ' . $email);

                            //} catch (Exception $e) {
                               // $message = 'error';
                               //Log::info('Data : ' . $e->getMessage());
                            //}
                        }
                    }


                }else{
                    //Log::info("No Orders Found.");
                }
            }
        }

        $job->delete();
    }

}
