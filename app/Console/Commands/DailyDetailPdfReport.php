<?php

namespace App\Console\Commands;

use App\DailyReportPdf;
use App\Http\Controllers\Api\v3\Apicontroller;
use App\Http\Controllers\ReportController;
use App\OrderDetails;
use App\Outlet;
use App\Owner;
use Carbon\Carbon;
use Illuminate\Console\Command;

use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Fill;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use TCPDF;

class DailyDetailPdfReport extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'daily:detailreport';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $outlets = Outlet::all();
        $result = array();
        if (isset($outlets) && sizeof($outlets) > 0) {
            foreach ($outlets as $outlet) {

                $yst_date = Carbon::yesterday()->format('Y-m-d');
                $check_daily_record = DailyReportPdf::where('outlet_id', $outlet->id)->where('report_date', $yst_date)->count();

                if ($check_daily_record == 0) {

                    $html = '';
                    $total_tax = 0;
                    $total_discount = 0;
                    $total_final = 0;
                    $total_person_visit = 0;
                    $total_subtotal = 0;
                    $excel_data = array();

                    $orders = OrderDetails::join("order_items", "order_items.order_id", "=", "orders.order_id")
                        /*->join("invoice_details as inv","inv.order_id","=","orders.order_id")*/
                        ->select('orders.*', 'order_items.item_quantity as Quantity', 'order_items.item_name as item_name'/*,"inv.total as inv_total","inv.discount as inv_discount","inv.sub_total as inv_sub_total","inv.round_off as inv_round_off"*/)
                        ->where('orders.table_start_date', '>=', Carbon::yesterday()->startOfDay())
                        ->where('orders.table_start_date', '<=', Carbon::yesterday()->endOfDay())
                        ->where('orders.outlet_id', '=', $outlet->id)
                        ->where('orders.invoice_no', '!=', '')
                        ->orderBy('orders.created_at', 'asc')
                        ->where('orders.cancelorder', '!=', 1)
                        ->get();

                    $itemlist = array();
                    $itemlist_excel = array();
                    $orders1 = array();
                    $n = 1;
                    if (sizeof($orders) > 0) {
                        foreach ($orders as $order) {
                            if (array_key_exists($order->order_id, $itemlist)) {
                                if ($order->Quantity > 1) {
                                    $itemlist[$order->order_id] = $itemlist[$order->order_id] . ",<br />" . $order->Quantity . " x " . strtoupper($order->item_name);
                                    $itemlist_excel[$order->order_id] = $itemlist_excel[$order->order_id] . ",\n" . $order->Quantity . " x " . strtoupper($order->item_name);
                                } else {
                                    $itemlist[$order->order_id] = $itemlist[$order->order_id] . ",<br />" . "1 x " . strtoupper($order->item_name);
                                    $itemlist_excel[$order->order_id] = $itemlist_excel[$order->order_id] . ",\n" . "1 x " . strtoupper($order->item_name);
                                }
                            } else {
                                if ($order->Quantity > 1) {
                                    $itemlist[$order->order_id] = $order->Quantity . " x " . strtoupper($order->item_name);
                                    $itemlist_excel[$order->order_id] = $order->Quantity . " x " . strtoupper($order->item_name);
                                } else {
                                    $itemlist[$order->order_id] = "1 x " . strtoupper($order->item_name);
                                    $itemlist_excel[$order->order_id] = "1 x " . strtoupper($order->item_name);
                                }
                                array_push($orders1, $order);
                            }
                        }
                        $outlet_details = Outlet::Outletbyid2($outlet->id);

                        $html = '<label style="text-align: center"><b>' . $outlet_details->name . '</b> ' . Carbon::yesterday()->format('d-m-Y(D)') . '</label>
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
                        foreach ($orders1 as $orders) {
                            $tax_total = 0;
                            $json_tax = json_decode($orders->tax_type);
                            if (sizeof($json_tax) > 0 && isset($json_tax)) {
                                foreach ($json_tax as $tx) {
                                    if (gettype($tx) == 'string')
                                        $tx1 = json_decode($tx);
                                    else
                                        $tx1 = $tx;
                                    foreach ($tx1 as $key1 => $t) {
                                        $tax_total += $t->calc_tax;
                                    }
                                }
                            }

                            $total_tax += $tax_total;
                            $total_discount += $orders->discount_value;
                            $total_final += floatval($orders->totalprice);
                            $total_subtotal += floatval($orders->totalcost_afterdiscount);
                            $total_person_visit += $orders->person_no;

                            $excel_data[$n]['Invoice'] = $orders->invoice_no;
                            $excel_data[$n]['Table No.'] = $orders->table_no;
                            $excel_data[$n]['No. Of Person Visited'] = $orders->person_no;
                            $excel_data[$n]['Order Type'] = ReportController::get_order_type($orders->order_type);
                            $excel_data[$n]['Item List'] = ucfirst($itemlist_excel[$orders->order_id]);
                            $excel_data[$n]['Sub Total'] = number_format($orders->totalcost_afterdiscount, 2);
                            $excel_data[$n]['Discount'] = number_format($orders->discount_value, 2);
                            $excel_data[$n]['Total Tax Amount'] = number_format($tax_total, 2);
                            $excel_data[$n]['Round Off'] = number_format($orders->round_off, 2);
                            $excel_data[$n]['Gross Total'] = number_format($orders->totalprice, 2);

                            $html .= '<tr style="text-align: center; font-size: smaller;">
                                            <td> &nbsp; ' . $orders->invoice_no . '</td>
                                            <td width="25px"> ' . $orders->table_no . ' &nbsp; </td>
                                            <td width="25px"> ' . $orders->person_no . ' &nbsp; </td>
                                            <td> &nbsp; ' . ReportController::get_order_type($orders->order_type) . ' &nbsp; </td>
                                            <td width="100px" style="text-align: left">' . ucfirst($itemlist[$orders->order_id]) . ' &nbsp; </td>
                                            <td style="text-align:right; padding-right:5px; "> &nbsp; ' . number_format($orders->totalcost_afterdiscount, 2) . ' &nbsp; </td>
                                            <td style="text-align:right; padding-right:5px; "> &nbsp; ' . number_format($orders->discount_value, 2) . ' &nbsp; </td>
                                            <td style="text-align:right; padding-right:5px; "> &nbsp; ' . number_format($tax_total, 2) . ' &nbsp; </td>
                                            <td style="text-align:right; padding:5px; "> &nbsp; ' . number_format($orders->round_off, 2) . ' &nbsp; </td>
                                            <td style="text-align:right; padding:5px; "> &nbsp; ' . number_format($orders->totalprice, 2) . ' &nbsp; </td>
                                        </tr>';
                            $n++;
                        }

                        $excel_data[$n]['Invoice'] = "";
                        $excel_data[$n]['Table No.'] = "Total ";
                        $excel_data[$n]['No. Of Person Visited'] = $total_person_visit;
                        $excel_data[$n]['Order Type'] = "";
                        $excel_data[$n]['Item List'] = "";
                        $excel_data[$n]['Sub Total'] = number_format($total_subtotal, 2);
                        $excel_data[$n]['Discount'] = number_format($total_discount, 2);
                        $excel_data[$n]['Total Tax Amount'] = number_format($total_tax, 2);
                        $excel_data[$n]['Round Off'] = "";
                        $excel_data[$n]['Gross Total'] = number_format($total_final, 2);

                        $html .= '<tr style="text-align:right; font-size: smaller;">
                                            <td colspan="2"> Total &nbsp; </td>
                                            <td> &nbsp; ' . $total_person_visit . ' &nbsp; </td>
                                            <td colspan="2"> &nbsp;  &nbsp; </td>
                                            <td> &nbsp; ' . number_format($total_subtotal, 2) . ' &nbsp; </td>
                                            <td> &nbsp; ' . number_format($total_discount, 2) . ' &nbsp; </td>
                                            <td> &nbsp; ' . number_format($total_tax, 2) . ' &nbsp; </td>
                                            <td> &nbsp; &nbsp; </td>
                                            <td> &nbsp; ' . number_format($total_final, 2) . ' &nbsp; </td>
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
                        $parent_path = storage_path() . '/daily_order_pdf/';
                        if (!is_dir($parent_path))
                            mkdir($parent_path, 0777);
                        $path = storage_path() . '/daily_order_pdf/' . $outlet->id . '/';
                        if (!is_dir($path))
                            $path_created = mkdir($path, 0777);
                        chmod(storage_path() . '/daily_order_pdf/', 0777);
                        chmod($path, 0777);
                        $filename = $path . '/Detail_Report_of_' . Carbon::yesterday()->format('d-m-Y') . '.pdf';

                        DailyReportPdf::savePdfData($outlet->id, 'Detail_Report_of_' . Carbon::yesterday()->format('d-m-Y') . '.pdf', $filename, $yst_date);
                        $pdf->output($filename, 'F');

                        $excel_name = 'Detail_excel_Report_of_' . Carbon::yesterday()->format('d-m-Y');

                        Excel::create($excel_name, function ($excel) use ($excel_data) {

                            $excel->sheet('Sheet1', function ($sheet) use ($excel_data) {

                                $sheet->cells('A1:J1', function ($cells) {
                                    $cells->setBackground('#E04833');
                                    $cells->setFontColor('#ffffff');
                                });

                                $sheet->setOrientation('landscape');
                                $sheet->fromArray($excel_data);
                                //$sheet->setAutoSize(true);
                            });
                            $excel->getActiveSheet()->getStyle('E1:E' . $excel->getActiveSheet()->getHighestRow())
                                ->getAlignment()->setWrapText(true);
                            foreach ($excel->getActiveSheet()->getRowDimensions() as $rd) {
                                $rd->setRowHeight(-1);
                            }
                        })->store('xls', $path);
                    }
                }
            }
        }
    }



    /**
     * Get the console command arguments.
     *
     * @return array
     */
	/*protected function getArguments()
	{
		return [
			['example', InputArgument::REQUIRED, 'An example argument.'],
		];
	}*/

    /**
     * Get the console command options.
     *
     * @return array
     */
    /*protected function getOptions()
	{
		return [
			['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
		];
	}*/
}
