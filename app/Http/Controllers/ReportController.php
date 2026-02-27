<?php namespace App\Http\Controllers;

use App\Attendance;
use App\Campaign;
use App\DailyReportPdf;
use App\DailySummary;
use App\Expense;
use App\Http\Controllers\Api\v3\Apicontroller;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\HttpClientWrapper;
use App\InvoiceBill;
use App\ItemRequest;
use App\Kot;
use App\Location;
use App\Menu;
use App\MenuTitle;
use App\order_details;
use App\OrderItem;
use App\OrderPaymentMode;
use App\Outlet;
use App\Outlet_Menu_Bind;
use App\OutletMapper;
use App\Owner;
use App\PaymentOption;
use App\Purchase;
use App\ResponseDeviation;
use App\Role;
use App\SendCloseCounterStatus;
use App\Sources;
use App\Staff;
use App\Tables;
use App\Timeslot;
use App\User;
use App\Utils;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use DateTime;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use phpDocumentor\Reflection\Types\Object_;
use Savitriya\Icici_upi\IciciUpiTxn;

class ReportController extends Controller {

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['home']]);
    }
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
    public function index()
    {
        $menu_owner=Owner::menuOwner();
        $menu_items['0'] = "All Items";
        $menu_items=Menu::getMenuByUserId($menu_owner)->lists('item','id');
        $menu_title = MenuTitle::getMenuTitleByCreatedBy($menu_owner);
        $m_titles = array();
        $m_titles['0'] = 'All Categories';
        foreach($menu_title as $title) {
            $m_titles[$title->id]=$title->title;
        }

        /*$mappers = OutletMapper::getOutletIdByOwnerId(Auth::User()->id);

        $mapper_arr=array();
        foreach($mappers as $mapper)
        {
            $mapper_arr[] = $mapper['outlet_id'];
        }
        $outlets=Outlet::whereIn('id',$mapper_arr)->get();

        $data['outlets']=array();
        foreach($outlets as $outlet) {
            $data['outlets'][$outlet->id]=$outlet->name;
        }*/
		$outlets = OutletMapper::getOutletsByOwnerId();

		if ( sizeof($outlets) == 2 ) {
			unset($outlets['']);
		}

        return view('report.index', array('items'=>$menu_items,'outlets'=>$outlets, 'menu_titles' => $m_titles));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show()
    {
        $menu_owner = Owner::menuOwner();
        $menu_items = Menu::getMenuByUserId($menu_owner)->lists('item','id');
        $selected_items = Input::get("item_id");
        $from_date = Input::get("from_date");
        $to_date = Input::get("to_date");
        $outlet_id = Input::get("outlet_id");
        $cat_id = Input::get('cat_id');
		$flag = Input::get('flag');
		$sess_outlet_id = Session::get('outlet_session');

        $blank = false;
        if ( (isset($selected_items[0]) && $selected_items[0] == '0') || $selected_items == '') {
            $blank = true;
        }
		if (isset($sess_outlet_id) && $sess_outlet_id != '') {
			$outlet_id = $sess_outlet_id;
		}
        if(((isset($selected_items[0]) && $selected_items[0] == '0') || $selected_items == '') && $cat_id != '0'){
            //$selected_items[] = 0;
            $selected_items = Menu::getmenubymenutitleid($cat_id)->lists('id');
        }
        if($cat_id == '0' && ((isset($selected_items[0]) && $selected_items[0] == '0') || $selected_items == '')){
            $selected_items = Menu::where('created_by','=',$menu_owner)->lists('id');
        }

        if ( $blank == true ) {
            // array_push($selected_items, 0);
            $selected_items->push(0);
        }

		//set session
		Session::set('from_session',$from_date);
		Session::set('to_session',$to_date);

        $date_array=$this->createDateRangeArray($from_date,$to_date);
        $result=[]; $selected_items1 = array();
        for($i=0;$i<sizeof($date_array);$i++){

            //convert to session time
            $carbon_start_date = Utils::getSessionTime($date_array[$i],'from');
            $carbon_end_date = Utils::getSessionTime($date_array[$i],'to');

            $items = OrderItem::join("orders", "orders.order_id", "=", "order_items.order_id")
                ->whereIn('order_items.item_id',$selected_items)
                ->select('order_items.id','orders.table_start_date','order_items.item_id as order_items_id', "order_items.item_name as item", DB::raw('ifnull(sum(order_items.item_quantity),0) as count'))
                ->where('orders.table_end_date','>=', $carbon_start_date)
                ->where('orders.table_end_date','<=', $carbon_end_date)
                ->where('orders.outlet_id','=',$outlet_id)
                ->where('orders.invoice_no',"!=",'')
                ->where('orders.cancelorder', '!=', 1)
                ->groupBy('order_items.item_name')
                ->get();


            foreach ($items as $item) {
                $result[$item->item][$date_array[$i]] = $item->count;
                if ( !in_array($item->item,$selected_items1)) {
                    $selected_items1[] = $item->item;
                }
            }
        }

        if( isset($result) && sizeof($result)>0 ) {

			$excel_data = array();
			$excel_name = 'Item_sales_report_'.$from_date."_to_".$to_date;
			if ( $flag == 'excel' ) {

				$begin = new DateTime( $from_date );
				$end = new DateTime( $to_date );
				$end = $end->modify( '+1 day' );

				$interval = new DateInterval('P1D');
				$daterange = new DatePeriod($begin, $interval ,$end);

				for( $i=0; $i<sizeof($selected_items1); $i++ ){

					$temp = 0; //Check whether item is sold or not
					$total_item = 0;  //Total items sold in selected duration
					//$item = Menu::where('item',$selected_items[$i])->first();

					foreach($daterange as $date) {
						if(isset($result[$selected_items1[$i]][$date->format("Y-m-d")])) {
                            $temp = 1;
						}
					}
					if($temp == 1) {

						$excel_data[$i]['Item'] = $selected_items1[$i];

						foreach ( $daterange as $date ) {

							if( !isset( $total[$date->format("Y-m-d")] ) || !$total[$date->format("Y-m-d")] > 0 ) {
								$total[$date->format("Y-m-d")] = "0";
							}

							if ( isset( $result[$selected_items1[$i]][$date->format("Y-m-d")] ) ) {
								$excel_data[$i][$date->format("Y-m-d")] = $result[$selected_items1[$i]][$date->format("Y-m-d")];
								$total_item += $result[$selected_items1[$i]][$date->format("Y-m-d")];


							} else {
								$excel_data[$i][$date->format("Y-m-d")] = "0";
							}

						}
					}
				}

				Excel::create($excel_name, function($excel) use($excel_data) {

					$excel->sheet('Sheet1', function($sheet) use($excel_data) {

						$sheet->row(1, function($row) {

							// call cell manipulation methods
							$row->setBackground('#BEFF33');
							$row->setFontWeight('bold');

						});
						$sheet->freezeFirstRowAndColumn();
						$sheet->setAutoSize(true);
						$sheet->setOrientation('landscape');
						$sheet->fromArray($excel_data);
					});

					$excel->getActiveSheet()->cells('A1:'.$excel->getActiveSheet()->getHighestColumn().'1', function ($cells){
						$cells->setBackground('#BEFF33');
						$cells->setFontWeight('bold');
					});


				})->export('xls');

			} else {

				$data['itemwise'] = $result;
				return view('report.item_day_report',array('itemwise'=>$data['itemwise'],'selected_items'=>$selected_items1,'from_date'=>$from_date,'to_date'=>$to_date));
			}

        }else{

            return view('report.item_day_report',array('itemwise'=>'','selected_items'=>$selected_items1,'from_date'=>$from_date,'to_date'=>$to_date));
        }

    }

    public function export_item_excel(){
        //$user_id=Input::get('restau_id');
        $menu_owner = Owner::menuOwner();
        $menu_items = Menu::getMenuByUserId($menu_owner)->lists('item','id');
        $selected_items = Input::get("item_id");
        $from_date = Input::get("from_date");
        $to_date = Input::get("to_date");
        $outlet_id = Input::get("outlet_id");

        $date_array=$this->createDateRangeArray($from_date,$to_date);
        $result=[];

        for($i=0;$i<sizeof($date_array);$i++){

            $carbon_start_date = (new Carbon($date_array[$i]))->startOfDay();
            $carbon_end_date = (new Carbon($date_array[$i]))->endOfDay();

            $items = DB::table('order_items')->join("orders", "orders.order_id", "=", "order_items.order_id")
                ->whereIn('order_items.item_id',$selected_items)
                ->select('order_items.id','orders.table_start_date','order_items.item_id as order_items_id', "order_items.item_name as item", DB::raw('ifnull(sum(order_items.item_quantity),0) as count'))
                ->where('orders.table_end_date','>=', $carbon_start_date)
                ->where('orders.table_end_date','<=', $carbon_end_date)
                ->where('orders.outlet_id','=',$outlet_id)
                ->where('orders.cancelorder', '!=', 1)
                ->groupBy('order_items.item_id')
                ->get();
            foreach($items as $item){
                $result[$item->item][$date_array[$i]] = $item->count;
            }
        }

        $itemwise = $result;

        $j=0;
        $result1=array();
        $begin = new DateTime( $from_date );
        $end = new DateTime( $to_date );
        $end = $end->modify( '+1 day' );

        $interval = new DateInterval('P1D');
        $daterange = new DatePeriod($begin, $interval ,$end);

        for($i=0;$i<sizeof($selected_items);$i++){

                $item= \App\Menu::find($selected_items[$i])->item;
                $result1[$j]['item'] = $item;

                foreach($daterange as $date){

                    if(isset($itemwise[$item][$date->format("Y-m-d")])){
                        $result1[$j][$date->format("M-d-D")] = $itemwise[$item][$date->format("Y-m-d")];
                    }else{
                        $result1[$j][$date->format("M-d-D")] = 0;
                    }
                }

            $j++;

        }

        ob_end_clean();
        ob_start();
        Excel::create('item_day_report', function($excel) use($result1) {
            $excel->sheet('Sheet1', function($sheet) use($result1) {

                $sheet->setOrientation('landscape');
                $sheet->fromArray($result1);
            });
        })->download('xls');

        $out = ob_get_contents();

        error_log($out);

        ob_end_clean();
    }

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
    function createDateRangeArray($strDateFrom,$strDateTo)
    {
        // takes two dates formatted as YYYY-MM-DD and creates an
        // inclusive array of the dates between the from and to dates.

        // could test validity of dates here but I'm already doing
        // that in the main script

        $aryRange=array();

        $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2), substr($strDateFrom,8,2),substr($strDateFrom,0,4));
        $iDateTo=mktime(1,0,0,substr($strDateTo,5,2), substr($strDateTo,8,2),substr($strDateTo,0,4));

        if ($iDateTo>=$iDateFrom)
        {
            array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
            while ($iDateFrom<$iDateTo)
            {
                $iDateFrom+=86400; // add 24 hours
                array_push($aryRange,date('Y-m-d',$iDateFrom));
            }
        }
        return $aryRange;
    }
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{

	}

	public function getsummaryreport(Request $request, $flag=null){

		$data=OutletMapper::getDetailReport($request,null);

		$lineChartData = $this->getLineChartReportData();
		$data['linechart'] = $lineChartData;

		return view('homenew', $data);

	}

	public function getdetailedreport(){
		$fromdate = Input::get("from_date");
		$todate = Input::get("to_date");
		$today_dt = strtotime($todate);
		$fromdate_dt = strtotime($fromdate);
		if ($today_dt < $fromdate_dt){
			return Redirect('/')->withInput(Input::all())->with('failure','ToDate must be greaterthan FromDate.');
		}
		$orders=DB::table("orders")
			->join("order_items","order_items.order_id","=","orders.order_id")
			->join("menus","menus.id","=","order_items.item_id")
			->select('orders.*','menus.item as item_name')
			//->whereRaw('date(orders.created_at) = ?', [$date] )
			->where('orders.table_end_date','>=', (new Carbon($fromdate))->startOfDay())
			->where('orders.table_end_date','<=', (new Carbon($todate))->endOfDay())
			->where('orders.cancelorder', '!=', 1)
			->get();



		//print_r($orders);exit;
		return json_encode($orders);
	}

	public function getLineChartReportData()
	{
		$lastdays = new Carbon(date('Y-m-d 23:59:59'));
		$startdate = Carbon::now()->startOfMonth();

        $date_arr = Utils::createDateRangeArray($startdate,$lastdays);

	    $outlet_id = Session::get('outlet_session');
        $graph_arr = array();

		if ( isset($outlet_id) ) {

            if ( isset($date_arr) && sizeof($date_arr) > 0 ) {
                foreach ( $date_arr as $arr ) {

                    $from = Utils::getSessionTime($arr,'from');
                    $to = Utils::getSessionTime($arr,'to');

                    $orders = order_details::where('table_end_date','>=', $from)
                                            ->where('table_end_date','<=', $to)
                                            ->where('orders.outlet_id', $outlet_id)
                                            ->where('cancelorder', '!=', 1)
                                            ->where('invoice_no',"!=",'');

                    $total[] = intval(str_replace(',', '', number_format($orders->sum('totalprice'),0)));
                    $count[] = $orders->count();
                    $date[] = date('d M',strtotime($arr));

                }
                $graph_arr[] = array('dates' => $date, 'count' => $count, 'revenue' => $total);
            }

		}

		return $graph_arr;
	}

    public function daily_report_pdf(){

		$outlets = OutletMapper::getOutletsByOwnerId();

		if ( sizeof($outlets) == 2 ) {
			unset($outlets['']);
		}

        return view('report.daily_report_pdf', array('outlets'=>$outlets));

    }

    public function get_detail_report_pdf(){

        $outlet_id = Input::get('outlet_id');
        $from_date = Input::get('from_date');
        $to_date = Input::get('to_date');

        $pdf_files=DailyReportPdf::where('outlet_id',$outlet_id)
				->where('report_date', '>=', $from_date)
				->where('report_date', '<=', $to_date)
				->get();

        if(sizeof($pdf_files)>0 && isset($pdf_files)){
            $daily_report_pdf = '<table border="1" id="pdf_table" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>File Name</th>
                                            <th>Download PDF</th>
                                            <th>Download EXCEL</th>
                                        </tr>
                                    </thead>
                                    <tbody>';

                                        for($i=0;$i<sizeof($pdf_files);$i++){ //$i = 2 because $files[0] = . & $files[1]=..
                                            $pdf_name = $pdf_files[$i]->name;
                                            $excel_name = str_replace("Detail_Report", "Detail_excel_Report", $pdf_files[$i]->name);
                                            $excel_name_ext = str_replace(".pdf", ".xls", $excel_name);
											$file_name = str_replace('_'," ",$pdf_files[$i]->name);
											$file_name = str_replace('.pdf','',$file_name);
                                            $daily_report_pdf .= '  <tr>
                                                <td>'. $file_name .'</td>
                                                <td><a href="/download_detail_report/'.$outlet_id.'/'.$pdf_files[$i]->name.'">Download</a></td>
                                                <td><a href="/download_detail_report/'.$outlet_id.'/'.$excel_name_ext.'">Download</a></td>
                                            </tr> ';
                                        }




                $daily_report_pdf .= '</tbody>
                                </table>';

            return $daily_report_pdf;
        }else{
            return '<label style="color: red;"> No Files and Data Available </label>';
        }

    }

    public function download_daily_report($param, $param1)
    {
        $file = storage_path('daily_order_pdf/'.$param).'/'.$param1;
        return response()->download($file);
    }

    public static function get_order_type($type){
        $types=array('take_away'=>'Take Away','dine_in'=>'Dine In','home_delivery'=>'Home Delivery','meal_packs'=>'Meal Packs');
        return $types[$type];
    }

    public function detail_discount_report(){

		$outlets = OutletMapper::getOutletsByOwnerId();

		if ( sizeof($outlets) == 2 ) {
			unset($outlets['']);
		}

        return view('report.detail_discount_report', array('outlets'=>$outlets));

    }

    public function ajax_detail_discount_report(){

        $outlet_id = Input::get('outlet_id');

		$sess_outlet_id = Session::get('outlet_session');

		if (isset($sess_outlet_id) && $sess_outlet_id != '') {
			$outlet_id = $sess_outlet_id;
		}

		$from_date = Input::get('from_date');
        $to_date = Input::get('to_date');

		Session::set('from_session',$from_date);
		Session::set('to_session',$to_date);

        //convert to session time
        $from = Utils::getSessionTime($from_date,'from');
        $to = Utils::getSessionTime($to_date,'to');

        $orders=DB::table("orders")
            ->join("order_items","order_items.order_id","=","orders.order_id")
            ->select('orders.*','order_items.item_name as item_name','order_items.item_quantity as Quantity','orders.discount_value')
            ->where('orders.invoice_no',"!=",'')
            ->where('orders.table_end_date','>=', $from)
            ->where('orders.table_end_date','<=', $to)
            ->where('orders.outlet_id','=',$outlet_id)
            ->orderBy('orders.order_id', 'desc')
            ->where('orders.cancelorder', '!=', 1)
            ->get();

        $itemlist=array();
        $data['orders']=array();
		foreach($orders as $order) {
			if (array_key_exists($order->order_id,$itemlist)) {
				if($order->Quantity>1){
					$itemlist[$order->order_id] = $itemlist[$order->order_id] . ",</br>" .$order->Quantity." x ".strtoupper($order->item_name);
				}else {
					$itemlist[$order->order_id] = $itemlist[$order->order_id] . ",</br>" ."1 x ".strtoupper($order->item_name);
				}
			} else {
				if($order->Quantity>1){
					$itemlist[$order->order_id] = $order->Quantity." x ".strtoupper($order->item_name);
				}else {
					$itemlist[$order->order_id] = "1 x ".strtoupper($order->item_name);
				}
				array_push($data['orders'],$order);
			}
		}

        $data['itemlist']=$itemlist;
        $html = '';
            $html .= '<table border="1" id="disc_table" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Order Type</th>
                                <th>Item Name</th>
                                <th>Amount</th>
                                <th>Discount Amount</th>
                                <th>Order Date</th>
                            </tr>
                        </thead>
                        <tbody>';
                $total_price = 0;
                $total_simple_disc = 0;
                $total_noncharg_disc = 0;
                $count = 0;
                for($i=0;$i<sizeof($data['orders']);$i++){
                    $total_price += $data['orders'][$i]->totalcost_afterdiscount;
                    if($data['orders'][$i]->totalprice == 0)
                        $total_noncharg_disc += $data['orders'][$i]->discount_value + $data['orders'][$i]->item_discount_value;
                    else
                        $total_simple_disc += $data['orders'][$i]->discount_value + $data['orders'][$i]->item_discount_value;

                    if(($data['orders'][$i]->discount_value + $data['orders'][$i]->item_discount_value) > 0) {
                        $count++;
                        $html .= '<tr>
                                <td>' . $data['orders'][$i]->invoice_no . '</td>
                                <td>' . ReportController::get_order_type($data['orders'][$i]->order_type) . '</td>
                                <td>' . $data['itemlist'][$data['orders'][$i]->order_id] . '</td>
                                <td>' . number_format($data['orders'][$i]->totalprice, 2) . '</td>
                                <td>' . number_format($data['orders'][$i]->discount_value + $data['orders'][$i]->item_discount_value, 2) . '</td>
                                <td>' . Carbon::parse($data['orders'][$i]->table_end_date)->format('d-m-Y  h:i A') . '</td>
                            </tr>';
                    }
                }
                if($count==0){
                    $html .= '<tr>
                                <td colspan="6">No Orders Found</td>
                            </tr>';
                }

        $html .= '</tbody>
            </table><br/><br/>';
        $html .= '<table class="table table-striped table-bordered table-hover" border="1" style="text-align:center">
                        <tr>
                            <td>Total Discount</td>
                            <td>Total Non Chargeable Order Amount</td>
                        </tr>
                        <tr>
                            <td>'.number_format($total_simple_disc,2).'</td>
                            <td>'.number_format($total_noncharg_disc,2).'</td>
                        </tr>
                    </table>';
        return $html;
    }

    public function ajax_summary_report(){
        $outlet_id = Input::get('outlet_id');
        $flag = Input::get('flag');

		$sess_outlet_id = Session::get('outlet_session');

		if (isset($sess_outlet_id) && $sess_outlet_id != '') {
			$outlet_id = $sess_outlet_id;
		}

		$from_date = Input::get('from_date');
		$to_date = Input::get('to_date');

		//set session
		Session::set('from_session',$from_date);
		Session::set('to_session',$to_date);

        $date_array = Utils::createDateRangeArray($from_date,$to_date);


            $excel_data = array();
            $n = 0;
            $total_orders = 0;
            $total_sells = 0;
            $total_discount = 0;
            $total_nc_order = 0;
            $total_sale_per_person = 0;
            $total_taxes = 0;
            $total_online = 0;
            $total_cash = 0;
            $total_cheque = 0;
            $total_unpaid = 0;
            $total_gross_total = 0;
            $total_gross_average = 0;
            $total_unique_item_sell = 0;
            $total_item_sell = 0;
            $total_person_visit = 0;
            $total_cancel_order_count = 0;
            $total_cancel_order_amount = 0;
            $today_unique_mobiles = 0;
            $date_data = array();
            $data = array();

            foreach ($date_array as $index=>$date) {

                $total_tax = 0.0;
                $unique_items = 0;

                //get date as per outlet sessions
                $outlet_session_from = Utils::getSessionTime(date('Y-m-d',strtotime($date)),'from');
                $outlet_session_to = Utils::getSessionTime(date('Y-m-d',strtotime($date)),'to');

                $excel_data[$n]['Report Date'] = date('Y-m-d',strtotime($date));
                //get order as per outlet session time
                $orders = order_details::where('orders.table_end_date', '>=', $outlet_session_from)
                    ->where('orders.table_end_date', '<=', $outlet_session_to)
                    ->where('outlet_id', '=', $outlet_id)
                    ->where('orders.invoice_no', "!=", '')
                    ->where('cancelorder', '!=', 1);

                $l_order = $orders->min('orders.totalprice');
                $h_order = $orders->max('orders.totalprice');
                $t_sell = $orders->sum('orders.totalcost_afterdiscount');
                $avg = $orders->avg('orders.totalprice');
                $t_orders = $orders->count();
                $g_total = $orders->sum('orders.totalprice');
                $t_person = $orders->sum('orders.person_no');

                //Total orders
                $excel_data[$n]['Total Orders'] = $t_orders or "-";
                $date_data[$date]['total_orders'] = $t_orders or 0;
                $total_orders += $t_orders;

                //Total sales
                $excel_data[$n]['Total Sales'] = $t_sell or "-";
                $date_data[$date]['total_sells'] = $t_sell or 0;
                $total_sells += $t_sell;

                $date_data[$date]['total_bifurcation'] = json_encode(array());

                $discount = 0;
                $nc = 0;
                $t_cash = 0;
                $t_prepaid = 0;
                $t_cheque = 0;
                $t_unpaid = 0;
                $t_person_visit = 0;
                $payment_opt = array();
                $data['total_discount'] = 0;
                $data['total_nc'] = 0;
                $data['top_selling_item'] = 0;
                $data['cancel_order'] = 0;
                $data['cancel_amount'] = 0;
                $active_items = 0;

                if (sizeof($order_arr = $orders->get()) > 0) {

                    foreach ($order_arr as $or) {
                        $discount_valueTotal = isset($or->discount_value) && !empty($or->discount_value) ? $or->discount_value : 0.00;
                        $item_discount_valueTotal = isset($or->item_discount_value) && !empty($or->item_discount_value) ? $or->item_discount_value : 0.00;
                        //get discount amount and non chargeable amount
                        $TotalDiscount = number_format((float)($discount_valueTotal + $item_discount_valueTotal), 2);
                        $disc_amt = $TotalDiscount;
                        $st_amt = floatval($or->totalcost_afterdiscount);
                        if ($disc_amt == '') {
                            $disc_amt = 0;
                        }
                        if ($or->totalprice == 0 ) {
                            $nc += $disc_amt;
                        } else {
                            $discount += $disc_amt;
                        }

                        //get total cash and prepaid amount
                        $payment_modes = OrderPaymentMode::where('order_id',$or->order_id)->get();

                        if ( isset($payment_modes) && !empty($payment_modes) ) {
                            foreach ( $payment_modes as $py_mode ) {

                                $check_payment_type = PaymentOption::find($py_mode->payment_option_id);
                                $source = Sources::find($py_mode->source_id);
                                $upi_status = false;
                                if ( isset($check_payment_type) && $check_payment_type != '' ) {

                                    if ( strtolower($check_payment_type->name) == 'cash' ) {
                                        $t_cash += $py_mode->amount;
                                    } else if ( strtolower($check_payment_type->name) == 'online' ) {

                                        if ( isset($source) && sizeof($source) > 0 ) {

//                                            if ( strtolower($source->name) == 'upi' ) {
//
//                                                //check payment status
//                                                $check_payment_status = IciciUpiTxn::where('status','=',1)->where('bill_no',$or->order_unique_id)->first();
//
//                                                if( isset($check_payment_status) && sizeof($check_payment_status) > 0 ) {
//                                                    $upi_status = true;
//                                                    $t_prepaid += $py_mode->amount;
//                                                } else {
//                                                    $t_unpaid += $py_mode->amount;
//                                                }
//
//                                            } else {
//                                                $t_prepaid += $py_mode->amount;
//                                            }
                                            $t_prepaid += $py_mode->amount;
                                        } else {
                                            $t_prepaid += $py_mode->amount;
                                        }

                                    } else if ( strtolower($check_payment_type->name) == 'cheque' ) {
                                        $t_cheque += $py_mode->amount;
                                    }

                                    if ( isset($source) && sizeof($source) > 0 ) {

//                                        if ( strtolower($source->name) == 'upi' ) {

                                            //check upi payment status

//                                            if( $upi_status == true ) {
//
//                                                if ( isset($payment_opt[$check_payment_type->name][$source->name])) {
//                                                    $payment_opt[$check_payment_type->name][$source->name] += $py_mode->amount;
//                                                } else {
//                                                    $payment_opt[$check_payment_type->name][$source->name] = $py_mode->amount;
//                                                }
//
//                                            } else {
//
//                                                //if payment status is not success than make it unpaid
//                                                if ( isset($payment_opt['UnPaid'])) {
//                                                    $payment_opt['UnPaid'] += $py_mode->amount;
//                                                } else {
//                                                    $payment_opt['UnPaid'] = $py_mode->amount;
//                                                }
//
//                                            }


//                                        } else {

                                            if ( isset($payment_opt[$check_payment_type->name][$source->name])) {
                                                $payment_opt[$check_payment_type->name][$source->name] += $py_mode->amount;
                                            } else {
                                                $payment_opt[$check_payment_type->name][$source->name] = $py_mode->amount;
                                            }

//                                        }


                                    } else {

                                        if ( isset($payment_opt[$check_payment_type->name]['direct'])) {
                                            $payment_opt[$check_payment_type->name]['direct'] += $py_mode->amount;
                                        } else {
                                            $payment_opt[$check_payment_type->name]['direct'] = $py_mode->amount;
                                        }

                                    }

                                } else {

                                    $t_unpaid += $py_mode->amount;

                                    if ( isset($payment_opt['UnPaid'])) {
                                        $payment_opt['UnPaid'] +=  $py_mode->amount;
                                    } else {
                                        $payment_opt['UnPaid'] =  $py_mode->amount;
                                    }
                                }

                            }
                        }

                        $tax_total = 0;
                        $json_tax = json_decode($or->tax_type);
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
                        $t_person_visit += $or->person_no;

                    }

                    $date_data[$date]['total_bifurcation'] = json_encode($payment_opt);

//                    $daily_summarry->total_bifurcation = json_encode($payment_opt);

                    $data['pay_options'] = $payment_opt;
                    $data['total_discount'] = number_format($discount, 0);
                    $data['total_nc'] = number_format($nc, 0);
                    $data['total_cash'] = number_format($t_cash, 0);
                    $data['total_prepaid'] = number_format($t_prepaid, 0);
                    $data['total_cheque'] = number_format($t_cheque, 0);
                    $data['total_unpaid'] = number_format($t_unpaid, 0);
                    $net_sale = $g_total - ($discount + $nc);
                    $data['net_sale'] = number_format($net_sale,0);

                    $avg_per_person = 0;
                    if ( isset($t_person_visit) && $t_person_visit > 0 ) {
                        $avg_per_person = floatval($g_total/$t_person_visit);
                    }
                    $data['avg_per_person'] =  number_format($avg_per_person,2);

                    //	Log::info("Total Orders ==> ".sizeof($orders));
                    $items = OrderItem::join("menus", "menus.id", "=", "order_items.item_id")
                        ->join("orders", "orders.order_id", "=", "order_items.order_id")
                        ->select('order_items.id', "menus.item", DB::raw('ifnull(sum(order_items.item_quantity),0) as count'))
                        //->whereBetween('order_items.created_at', array($from_date, $to_date))
                        ->where('orders.table_end_date', '>=', $outlet_session_from)
                        ->where('orders.table_end_date', '<=', $outlet_session_to)
                        ->where('orders.outlet_id', '=', $outlet_id)
                        ->where('orders.cancelorder', '!=', 1)
                        ->where('orders.invoice_no', "!=", '')
                        ->groupBy('item_id')
                        ->get();

                    $unique_items = DB::table("order_items")
                        ->join("orders", "orders.order_id", "=", "order_items.order_id")
                        ->select('order_items.id', "order_items.item_name as item", DB::raw('ifnull(sum(order_items.item_quantity),0) as count'))
                        ->where('orders.table_end_date', '>=', $outlet_session_from)
                        ->where('orders.table_end_date', '<=', $outlet_session_to)
                        ->where('orders.outlet_id', '=', $outlet_id)
                        ->where('orders.cancelorder', '!=', 1)
                        ->where('orders.invoice_no', "!=", '')
                        ->groupBy('order_items.item_name')->get();

                    $data['top_selling_item'] = "";
                    $count = 0;
                    $total_item_sell = 0;
                    foreach ( $items as $item) {
                        if ( $item->count > $count) {
                            $count = $item->count;
                            $data['top_selling_item'] = ucfirst($item->item);
                        }
                        $total_item_sell += $item->count;
                    }

                    $cancel_order = order_details::leftJoin('order_cancellation_mapper as ocm', 'ocm.order_id', '=', 'orders.order_id')
                        ->leftJoin('owners as o', 'o.id', '=', 'ocm.created_by')
                        ->select('orders.*', 'ocm.reason as reason', 'o.user_name as user_name')
                        ->where('orders.table_end_date', '>=', $outlet_session_from)
                        ->where('orders.table_end_date', '<=', $outlet_session_to)
                        ->where('orders.outlet_id', '=', $outlet_id)
                        ->where('orders.cancelorder', '=', 1);

                    $c_order_amt = $cancel_order->sum('orders.totalprice');
                    $c_order_cnt = $cancel_order->count();

                    $data['cancel_order'] = $c_order_cnt;
                    $data['cancel_amount'] = number_format($c_order_amt, 0);
                    $data['cancel_order_arr'] = $cancel_order->get();

                }

                $active_items = Outlet_Menu_Bind::where('outlet_menu_bind.outlet_id', $outlet_id)
                    ->join("menus", "menus.id", "=", "outlet_menu_bind.menu_id")
                    ->where("menus.active", 0)->get();

                //Total Discount
                $excel_data[$n]['Total Discount'] = $data['total_discount']==0?"NA":$data['total_discount'];
                $date_data[$date]['total_discount'] = $data['total_discount'] or 0;
                $total_discount += $data['total_discount'];

                //Total Nonchargeable orders
                $excel_data[$n]['Total NC Orders'] = $data['total_nc']==0?"NA":$data['total_nc'];
                $date_data[$date]['total_nc_order'] = $data['total_nc'] or 0;
                $total_nc_order += $data['total_nc'];

                //Total tax collected
                $excel_data[$n]['Total Taxes'] = $total_tax==0?"NA":$total_tax;
                $date_data[$date]['total_taxes'] = $total_tax or 0;
                $total_taxes += $total_tax;

                //Total online payment collected
                $excel_data[$n]['Total Online'] = $t_prepaid==0?"NA":$t_prepaid;
                $date_data[$date]['total_online'] = $t_prepaid or 0;
                $total_online += $t_prepaid;

                //Total Cash collected
                $excel_data[$n]['Total Cash'] = $t_cash==0?"NA":$t_cash;
                $date_data[$date]['total_cash'] = $t_cash or 0;
                $total_cash += $t_cash;

                //Total Cheque collected
                $excel_data[$n]['Total Cheque'] = $t_cheque==0?"NA":$t_cheque;
                $total_cheque += $t_cheque;

                //Total Unpaid orders
                $excel_data[$n]['Total Unpaid'] = $t_unpaid==0?"NA":$t_unpaid;
                $total_unpaid += $t_unpaid;

                //GrossTotal of the day
                $excel_data[$n]['Gross Total'] = $g_total==0?"NA":$g_total;
                $date_data[$date]['gross_total'] = $g_total or 0;
                $total_gross_total += $g_total;

                $excel_data[$n]['Gross Average'] = $avg==0?"NA":$avg;
                $date_data[$date]['gross_average'] = $avg or 0;
                $total_gross_average += $avg;

                if(isset($unique_items)){
                    $date_data[$date]['total_unique_item_sell'] =  isset($unique_items) or 0;
                    $excel_data[$n]['Total Unique Items Sell'] = isset($unique_items)==0 ? "NA" : isset($unique_items);
                }else{
                    $date_data[$date]['total_unique_item_sell'] =  0;
                    $excel_data[$n]['Total Unique Items Sell'] = 0;
                }
                $total_unique_item_sell += isset($unique_items);

                $excel_data[$n]['Total Items Sell'] = $total_item_sell==0 ? "NA" : $total_item_sell;
                $date_data[$date]['total_item_sell'] = $total_item_sell;
                $total_item_sell += $total_item_sell;

                //Total Person visit
                $excel_data[$n]['Total Persons Visits'] = $t_person==0 ? "NA" : $t_person;
                $date_data[$date]['total_person_visit'] =  $t_person;
                $total_person_visit += $t_person;

                $excel_data[$n]['Top Selling Item'] = $data['top_selling_item']=="" ? "NA" : $data['top_selling_item'];
                $date_data[$date]['top_selling_item'] =  $data['top_selling_item'];

                //Lowest Order of the day
                $excel_data[$n]['Lowest Order'] = $l_order==0 ? "NA" : $l_order;
                $date_data[$date]['lowest_order'] =  $l_order;

                //Highest order of the day
                $excel_data[$n]['Highest Order'] = $h_order==0 ? "NA" : $h_order;
                $date_data[$date]['highest_order'] =  $h_order;

                $excel_data[$n]['Cancel Order'] = $data['cancel_order']==0 ? "NA" : $data['cancel_order'];
                $date_data[$date]['cancel_order_count'] =  $data['cancel_order'];
                $total_cancel_order_count += $data['cancel_order'];

                $excel_data[$n]['Cancel Order Amount'] = $data['cancel_amount']==0 ? "NA" : $data['cancel_amount'];
                $date_data[$date]['cancel_order_amount'] =  $data['cancel_amount'];

                $total_cancel_order_amount = (int) $total_cancel_order_amount + (int) $data['cancel_amount'];

                if(isset($active_items)) {
                    $excel_data[$n]['Active Items'] = sizeof($active_items) == 0 ? "NA" : sizeof($active_items);
                    $date_data[$date]['active_item'] = sizeof($active_items);
                }else{
                    $excel_data[$n]['Active Items'] = 0;
                    $date_data[$date]['active_item'] = 0;
                }

                if ( $t_person == 0 || $t_person == '') {
                    $excel_data[$n]['Total Sale Per Person'] = 0;
                } else {
                    $excel_data[$n]['Total Sale Per Person'] = $g_total / $t_person;
                }

                //today's unique number
                $today_unique_mobile = order_details::join("users as u", "u.id", "=", "orders.user_id")
                    ->where('orders.table_end_date', '>=', $outlet_session_from)
                    ->where('orders.table_end_date', '<=', $outlet_session_to)
                    ->where('outlet_id', '=', $outlet_id)
                    ->where('orders.invoice_no', "!=", '')
                    ->where('cancelorder', '!=', 1)
                    ->where('u.mobile_number','!=',0)
                    ->distinct()->count('u.mobile_number');

                //total unique number
                $total_unique_mobile = order_details::join("users as u", "u.id", "=", "orders.user_id")
                    ->where('outlet_id', '=', $outlet_id)
                    ->where('orders.invoice_no', "!=", '')
                    ->where('cancelorder', '!=', 1)
                    ->where('u.mobile_number','!=',0)
                    ->distinct()->count('u.mobile_number');


                $excel_data[$n]['Today Unique Mobiles'] = $today_unique_mobile==0?"NA":$today_unique_mobile;
                $today_unique_mobiles += $today_unique_mobile;
                $excel_data[$n]['Total Unique Mobiles'] = $total_unique_mobile==0?"NA":$total_unique_mobile;
                $n++;

            }

            $excel_data[$n]['Report Date'] = "Total :";
            $excel_data[$n]['Total Orders'] = $total_orders==0?"NA":$total_orders;
            $excel_data[$n]['Total Sales'] = $total_sells==0?"NA":$total_sells;
            $excel_data[$n]['Total Discount'] = $total_discount==0?"NA":$total_discount;
            $excel_data[$n]['Total NC Orders'] = $total_nc_order==0?"NA":$total_nc_order;
            $excel_data[$n]['Total Taxes'] = $total_taxes==0?"NA":$total_taxes;
            $excel_data[$n]['Total Online'] = $total_online==0?"NA":$total_online;
            $excel_data[$n]['Total Cash'] = $total_cash==0?"NA":$total_cash;
            $excel_data[$n]['Total Cheque'] = $total_cheque==0?"NA":$total_cheque;
            $excel_data[$n]['Total Unpaid'] = $total_unpaid==0?"NA":$total_unpaid;
            $excel_data[$n]['Gross Total'] = $total_gross_total==0?"NA":$total_gross_total;
            $excel_data[$n]['Gross Average'] = $total_gross_average==0?"NA":$total_gross_average;
            $excel_data[$n]['Total Unique Items Sell'] = $total_unique_item_sell==0?"NA":$total_unique_item_sell;
            $excel_data[$n]['Total Items Sell'] = $total_item_sell==0?"NA":$total_item_sell;
            $excel_data[$n]['Total Persons Visits'] = $total_person_visit==0?"NA":$total_person_visit;
            $excel_data[$n]['Top Selling Item'] = "NA";
            $excel_data[$n]['Lowest Order'] = "NA";
            $excel_data[$n]['Highest Order'] = "NA";
            $excel_data[$n]['Cancel Order'] = $total_cancel_order_count==0?"NA":$total_cancel_order_count;
            $excel_data[$n]['Cancel Order Amount'] = $total_cancel_order_amount==0 ? "NA" : $total_cancel_order_amount;
            $excel_data[$n]['Active Items'] = "NA";
            $excel_data[$n]['Total Sale Per Person'] = "NA";
            $excel_data[$n]['Today Unique Mobiles'] = $today_unique_mobiles==0?"NA":$today_unique_mobiles;
            $excel_data[$n]['Total Unique Mobiles'] = "NA";


            $excel_name = 'Summary_excel_report_of_' . $from_date . "_to_" . $to_date;

        if($flag == 'export') {
            Excel::create($excel_name, function ($excel) use ($excel_data) {
                $excel->sheet('Sheet1', function($sheet) use($excel_data) {
                    $sheet->cells('B', function ($cells){
                        $cells->setAlignment('right');
                    });
                    $sheet->cells('C', function ($cells){
                        $cells->setAlignment('right');
                    });
                    $sheet->cells('D', function ($cells){
                        $cells->setAlignment('right');
                    });
                    $sheet->cells('E', function ($cells){
                        $cells->setAlignment('right');
                    });
                    $sheet->cells('F', function ($cells){
                        $cells->setAlignment('right');
                    });
                    $sheet->cells('G', function ($cells){
                        $cells->setAlignment('right');
                    });
                    $sheet->cells('H', function ($cells){
                        $cells->setAlignment('right');
                    });
                    $sheet->cells('I', function ($cells){
                        $cells->setAlignment('right');
                    });
                    $sheet->cells('J', function ($cells){
                        $cells->setAlignment('right');
                    });
                    $sheet->cells('K', function ($cells){
                        $cells->setAlignment('right');
                    });
                    $sheet->cells('L', function ($cells){
                        $cells->setAlignment('right');
                    });
                    $sheet->cells('M', function ($cells){
                        $cells->setAlignment('right');
                    });
                    $sheet->cells('N', function ($cells){
                        $cells->setAlignment('right');
                    });
                    $sheet->cells('O', function ($cells){
                        $cells->setAlignment('right');
                    });
                    $sheet->cells('Q', function ($cells){
                        $cells->setAlignment('right');
                    });
                    $sheet->cells('R', function ($cells){
                        $cells->setAlignment('right');
                    });
                    $sheet->cells('S', function ($cells){
                        $cells->setAlignment('right');
                    });
                    $sheet->cells('T', function ($cells){
                        $cells->setAlignment('right');
                    });
                    $sheet->cells('U', function ($cells){
                        $cells->setAlignment('right');
                    });
                    $sheet->cells('V', function ($cells){
                        $cells->setAlignment('right');
                    });
                    $sheet->cells('W', function ($cells){
                        $cells->setAlignment('right');
                    });
                    $sheet->cells('X', function ($cells){
                        $cells->setAlignment('right');
                    });
                    $sheet->setOrientation('landscape');
                    $sheet->fromArray($excel_data);
                });
                $excel->getActiveSheet()->cells('A1:'.$excel->getActiveSheet()->getHighestColumn()."1",function ($cells){
                    $cells->setBackground('#E04833');
                    $cells->setFontColor('#ffffff');

                });
                $excel->getActiveSheet()->cells('A'.$excel->getActiveSheet()->getHighestRow().':'.$excel->getActiveSheet()->getHighestColumn().$excel->getActiveSheet()->getHighestRow(), function ($cells){
                    $cells->setBackground('#BEFF33');
                    $cells->setFontWeight('bold');
                });
            })->download('xls');
        }

        return view('report.summary_table', array('summary' => $date_data));
    }

    public function summary_report(){

		$outlets = OutletMapper::getOutletsByOwnerId();

		if ( sizeof($outlets) == 2 ) {
			unset($outlets['']);
		}

        return view('report.daily_summary_report', array('outlets'=>$outlets));
    }

    public function selectslots(){

        $outlet_id = Input::get('outlet_id');
        $timeslots = Timeslot::gettimeslotbyoutletid($outlet_id);

        return $timeslots;
    }

	public function snapshot(Request $request) {

		if ($request->ajax()) {

			$from_date = Input::get('from_date');
			$to_date = Input::get('to_date');
			$outlet_id = Input::get('outlet_id');

			$sess_outlet_id = Session::get('outlet_session');
			Session::set('from_session',$from_date);
			Session::set('to_session',$to_date);

			if (isset($sess_outlet_id) && $sess_outlet_id != '') {
				$outlet_id = $sess_outlet_id;
			}
			$data = array();

			$date_arr = Utils::createDateRangeArray($from_date,$to_date);

			if ( isset($date_arr) && sizeof($date_arr) > 0 ) {

				//get outlet locations
				$locations  = Location::where('outlet_id',$outlet_id)->get();

				$loc_ids = array();
				if ( isset($locations) && sizeof($locations) > 0 ) {

					foreach( $locations as $loc ) {
							$loc_ids[] = $loc->id;
					}
				}

				foreach( $date_arr as $dt ) {

                    //convert to session time
                    $from = Utils::getSessionTime($dt,'from');
                    $to = Utils::getSessionTime($dt,'to');

					if( isset($loc_ids) && sizeof($loc_ids) > 0 ) {
						$purchase = InvoiceBill::whereIn('location_id',$loc_ids)->whereDate('invoice_date','=',$dt)->sum('total');
					} else {
						$purchase = 0;
					}

					$expense = Expense::where('expense_for',$outlet_id)
										->where('expense_date',$dt)
										->where('type','expense')
										->whereRaw('( status = "verified" || status = "paid" )')->sum('amount');

					$sales = order_details::where('outlet_id',$outlet_id)
                                            ->where('orders.table_end_date','>=', $from)
                                            ->where('orders.table_end_date','<=', $to)
											->where('cancelorder', '!=', 1)
											->where('invoice_no', '!=', '')->sum('totalprice');


					//stock transferred
					$stock_transferred = 0;

					if ( isset($loc_ids) && sizeof($loc_ids) > 0 ) {

						//get request for location
						$item_requests = ItemRequest::select('item_request.*')
							->whereBetween('satisfied_when', array($from, $to))
							->where('item_request.satisfied', '=', 'Yes')
							->whereIn('item_request.location_for',$loc_ids)
							->get();


						if (isset($item_requests) && sizeof($item_requests) > 0) {
							foreach ($item_requests as $req) {

								$satisfy_qty = $req->statisfied_qty;
								$menu_item = Menu::find($req->what_item_id);

								if (isset($menu_item->secondary_units) && $menu_item->secondary_units != '') {
									$units = json_decode($menu_item->secondary_units);
									if (isset($units) && $units != '') {
										foreach ($units as $key => $u) {
											if ($key == $req->satisfied_unit_id) {
												$satisfy_qty = floatval($req->statisfied_qty) * floatval($u);
											}
										}
									}

								}

								//get transferred price
								$stock_transferred += $satisfy_qty * $req->price;

							}
						}

					}

					//staff cost
					$staff_cost = 0;$salary = array();

					$staffs = Staff::where('outlet_id',$outlet_id)->get();

					if ( isset($staffs) && sizeof($staffs) > 0 ) {

						foreach( $staffs as $stf ) {

							$time = "00:00:00";
							$attendance = Attendance::where('staff_id',$stf->id)->whereDate('created_at','=',$dt)->get();
							if ( isset($attendance) && sizeof($attendance) > 0 ) {

								foreach( $attendance as $att ) {

									$from = $att->in_time; $to = $att->out_time;
									if ( isset($to) && $to != '' ) {

										$date1 = new DateTime($from);
										$date2 = new DateTime($to);
										$interval = $date1->diff($date2);
										$elapsed = $interval->format('%h:%i:%S');

										$secs = strtotime($elapsed)-strtotime("00:00:00");
										$time = date("H:i:s",strtotime($time)+$secs);

									}

								}

							}
							$data[$dt][$stf->id] = $time;

							//calculate salary
							if ( isset($stf->per_day) && isset($stf->per_day_hours) && $time != '00:00:00' && $stf->per_day_hours != '00:00:00' && $stf->per_day > 0 ) {

								$full_time = $half_time = $spent_time = 0;
								$time_arr = explode(':',$stf->per_day_hours);
								if ( isset($time_arr) && sizeof($time_arr) > 0 ) {

									$full_time = $time_arr[0] * 60;
									$full_time = $full_time + $time_arr[1];
									$half_time = $full_time / 2;

									$spent_arr = explode(':',$time);
									$spent_time = $spent_arr[0] * 60;
									$spent_time = $spent_time + $spent_arr[1];

								}

								if ( $spent_time >= $full_time  ) {

									if(!isset($salary[$stf->id]))
										$salary[$stf->id] = $stf->per_day;
									else
										$salary[$stf->id] += $stf->per_day;

								} elseif( $spent_time >= $half_time && $spent_time != 0 ) {

									if(!isset($salary[$stf->id]))
										$salary[$stf->id] = $stf->per_day / 2;
									else
										$salary[$stf->id] += $stf->per_day / 2;

								} else {

									if(!isset($salary[$stf->id]))
										$salary[$stf->id] = 0.00;
									else
										$salary[$stf->id] += 0.00;

								}

							} else {

								if(!isset($salary[$stf->id]))
									$salary[$stf->id] = 0.00;
								else
									$salary[$stf->id] += 0.00;

							}
							//echo $stf->name."==".$salary[$stf->id];exit;
							$staff_cost += $salary[$stf->id];
						}

					}

					$data[$dt]['sale'] = $sales;
					$data[$dt]['expense'] = $expense;
					$data[$dt]['purchase'] = $purchase;
					$data[$dt]['stock_transferred'] = $stock_transferred;
					$data[$dt]['staff_cost'] = $staff_cost;

				}
			}

			return view('report.snapshotList',array('data'=>$data,'dates'=>$date_arr));

		}

		$outlets = OutletMapper::getOutletsByOwnerId();

		if ( sizeof($outlets) == 2 ) {
			unset($outlets['']);
		}

		return view('report.snapshot',array('outlets'=>$outlets));
	}

    public function revenueReport(Request $request) {

        if ($request->ajax()) {

            $from_date = Input::get('from_date');
            $to_date = Input::get('to_date');

            $data = array();
            $table_header = array();
            $i=0;

			Session::set('from_session',$from_date);
			Session::set('to_session',$to_date);

            $outlet_id = Session::get('outlet_session');

			if ( $outlet_id == '' ) {
				return view('report.revenueReportList');
			}

            $outlet_payment_options = Outlet::find($outlet_id);
            $payment_option_json = $outlet_payment_options['payment_options'];

            if ( sizeof($payment_option_json)>0 && $payment_option_json != null) {

                $payment_option_array = json_decode($payment_option_json, true );
                $outlet_options = array_keys($payment_option_array);
                $outlet_options_arr = array();
                $outlet_source_arr = array();

                foreach ($outlet_options as $id => $option_id) {                           //outlet wise data

                    $outlet_options_arr[$option_id] = PaymentOption::find($option_id)['name'];
                    //$get_source = json_decode($payment_option_json, true);
                    $outlet_src_arr[$option_id] = $payment_option_array[$option_id];

                    $outlet_source_arr[$option_id][0] = '';
                    $check_option = $payment_option_array[$option_id][0];
                    if ( isset($check_option) && $check_option == "" ) {
                        continue;
                    }                                              //only payment option

                    if (sizeof($outlet_src_arr[$option_id]) > 0 && $outlet_src_arr[$option_id] != $option_id ) {

                        //if only payment option is selected
                        foreach ($outlet_src_arr[$option_id] as $id => $source_id) {
                            $outlet_source_arr[$option_id][$source_id] = Sources::find($source_id)['name'];
                        }
                    }

                }

                //Table Columns
                $outlet_options_arr[0] = 'UnPaid';
                $outlet_source_arr[0] = '';
                $data['source'] = $outlet_source_arr;
                $data['payment_option'] = $outlet_options_arr;

                //Table Header
                foreach ($outlet_options_arr as $po_id => $option) {
                    if ($po_id == 0) {
                        $table_header[$i++] = 'Unpaid';
                    } else {
                        foreach ($outlet_source_arr[$po_id] as $s_id => $source) {
                            if ($s_id != 0)
                                $table_header[$i++] = $option . ' - ' . $source;
                            else
                                $table_header[$i++] = $option;
                        }
                    }
                }
                $data['table_head'] = $table_header;

                //Table Raws
                $date_arr = Utils::createDateRangeArray($from_date, $to_date);

                $payment_opt = PaymentOption::all();

                if (isset($date_arr) && sizeof($date_arr) > 0) {
                    foreach ($date_arr as $dt) {

                        //convert to session time
                        $from = Utils::getSessionTime($dt,'from');
                        $to = Utils::getSessionTime($dt,'to');

                        foreach ($outlet_options_arr as $po_id => $option) {

                            if ($po_id == 0) {                                    //unpaid orders

                                $result = order_details::join('order_payment_modes as opm','orders.order_id','=','opm.order_id')
                                                        ->where('orders.outlet_id', $outlet_id)
                                                        ->where('orders.table_end_date','>=', $from)
                                                        ->where('orders.table_end_date','<=', $to)
                                                        ->where('orders.cancelorder', '!=', 1)
                                                        ->where('orders.invoice_no', '!=', '')
                                                        ->where('opm.source_id', 0)
                                                        ->where('opm.payment_option_id', 0)
                                                        ->selectRaw('sum(opm.amount) as sum')
                                                        ->get();

                                $data[$dt][0][0] = $result[0]['sum'];

                            } else {                                             //source and payment method both are selected

                                foreach ($outlet_source_arr[$po_id] as $s_id => $source) {

                                    if ($s_id != 0) {

                                        $result = order_details::join('order_payment_modes as opm','orders.order_id','=','opm.order_id')
                                                                ->where('orders.outlet_id', $outlet_id)
                                                                ->where('orders.table_end_date','>=', $from)
                                                                ->where('orders.table_end_date','<=', $to)
                                                                ->where('orders.cancelorder', '!=', 1)
                                                                ->where('orders.invoice_no', '!=', '')
                                                                ->where('opm.source_id', $s_id)
                                                                ->where('opm.payment_option_id', $po_id)
                                                                ->selectRaw('sum(opm.amount) as sum')
                                                                ->get();

                                        $data[$dt][$po_id][$s_id] = $result[0]['sum'];

                                    } else {                                      //only source is selected

                                        $result = order_details::join('order_payment_modes as opm','orders.order_id','=','opm.order_id')
                                                                ->where('orders.outlet_id', $outlet_id)
                                                                ->where('orders.table_end_date','>=', $from)
                                                                ->where('orders.table_end_date','<=', $to)
                                                                ->where('orders.cancelorder', '!=', 1)
                                                                ->where('orders.invoice_no', '!=', '')
                                                                ->where('opm.source_id', 0)
                                                                ->where('opm.payment_option_id', $po_id)
                                                                ->selectRaw('sum(opm.amount) as sum')
                                                                ->get();

                                        $data[$dt][$po_id][0] = $result[0]['sum'];
                                    }
                                }
                            }
                        }
                    }
                }
                //print_r($data);exit;
                return view('report.revenueReportList', array('data' => $data, 'dates' => $date_arr));
            }else{
                return view('report.revenueReportList', array('error' => 'Payment Methods not selected.'));
            }
        }

        $outlets = OutletMapper::getOutletsByOwnerId();

        if ( sizeof($outlets) == 2 ) {
            unset($outlets['']);
        }

        return view('report.revenueReport',array('outlets'=>$outlets));

    }

	public function expenseReport(Request $request) {

        $hasOwner = Owner::hasCreatedBy();
        $users = array();

		$all_user = Owner::where('created_by', Auth::id())->lists('id');
		$all_user[] = Auth::id();

        if ($hasOwner) {
            $users = Owner::where('id', Auth::id())->lists('user_name', 'id');
        } else {

			$users = Owner::where('created_by', Auth::id())->lists('user_name', 'id');
            $users[Auth::id()] = Auth::user()->user_name;
        }

        if ($request->ajax()) {

            $from_date = Input::get('from_date');
            $to_date = Input::get('to_date');
            $outlet_id = Input::get('outlet_id');
            $user_id = Input::get('user_id');
            $status_id = Input::get('status_id');

            $status_arr = Expense::getStatus();
            $status = isset($status_arr[$status_id])?$status_arr[$status_id]:'all';


			$sess_outlet_id = Session::get('outlet_session');

			Session::set('from_session',$from_date);
			Session::set('to_session',$to_date);

			if (isset($sess_outlet_id) && $sess_outlet_id != '') {
				$outlet_id = $sess_outlet_id;
			}

			$expense = Expense::whereBetween('expense_date', array($from_date, $to_date))
				->where('expense_for', $outlet_id)
				->orderBy('expense_date')
				->orderBy('expense_by');

			if ( $user_id != 'all'  && $status != 'all' ) {

				$result = $expense->where('expense_by', $user_id)->where('status',strtolower($status))->get();

			} else if ( $user_id != 'all' && $status == 'all' ) {

				$result = $expense->where('expense_by', $user_id)->get();

			} else if ( $user_id == 'all' && $status != 'all' ) {

				$result = $expense->whereIn('expense_by', $all_user)
								->where('status', strtolower($status))
								->get();

			} else if ( $user_id == 'all' && $status == 'all' ) {

				$result = $expense->whereIn('expense_by', $all_user)->get();

			}

			//opening date
			$opening_date = date('Y-m-d',strtotime($from_date .' -1 day'));

			//total expense till from date
			$balance_exp = Expense::where('expense_date','<', $opening_date)
								->where('expense_for', $outlet_id)
								->where('type','expense');

			if ( $user_id != 'all' ) {
				$exp_result = $balance_exp->where('expense_by',$user_id)->sum('amount');
			} else {
				$exp_result = $balance_exp->whereIn('expense_by', $all_user)->sum('amount');
			}

			//total cash till from date
			$balance_cash = Expense::where('expense_date','<', $opening_date)
									->where('expense_for', $outlet_id)
									->where('type','cash');

			if ( $user_id != 'all' ) {
				$cash_result = $balance_cash->where('expense_by',$user_id)->sum('amount');
			} else {
				$cash_result = $balance_cash->whereIn('expense_by', $all_user)->sum('amount');
			}

			//opening balance
			$opening_balance = $cash_result - $exp_result;


            $date_arr = Utils::createDateRangeArray($from_date,$to_date);

            return view('report.expenseReportList',array('date_range'=>$date_arr,'expenses'=>$result,'opening_balance'=>$opening_balance,'opening_date'=>$opening_date ));

        }else {

			/*$outlets = OutletMapper::getOutletsByOwnerId();
            if (sizeof($outlets) == 2) {
                unset($outlets['']);
            } else {
                unset($outlets['']);
                $outlets['all'] = 'All Outlets';
            }*/

            if (!$hasOwner){
                $users['all'] = 'All Users';
            }
            $status = Expense::getStatus();
            $status['all'] = 'All';

            return view('report.expenseReport', array(/*'outlets' => $outlets,*/ 'users' => $users, 'status'=>$status));
        }
	}

	public function purchaseRateReport(Request $request) {

		$owner_id = Auth::id();

		if ($request->ajax()) {

			$cat_id = Input::get('cat_id');
			$from_date = Input::get('from_date');
			$to_date = Input::get('to_date');
			$item_id = Input::get('item_id');
			$show_qty = Input::get('show_qty');
			$show_total = Input::get('show_total');
			$flag = Input::get('flag');
			$data = array();$response = array();
			$menu_owner = Owner::menuOwner();
			$itm_id_arr= array();$itm_name_arr = array();
			$date_arr = Utils::createDateRangeArray($from_date,$to_date);

			Session::set('from_session',$from_date);
			Session::set('to_session',$to_date);

			if ( isset($item_id) && $item_id != '' ) {
				$items = Menu::where('id',$item_id)->get();

			} else {
				$items = Menu::where('menu_title_id',$cat_id)->get();
                if($cat_id == 'all') {
                    $user_cat = MenuTitle::where('created_by', $menu_owner)->lists('id');
                    $items = Menu::whereIn('menu_title_id',$user_cat)->get();
                }
            }

			if ( isset($items) && sizeof($items) > 0 ) {

				foreach( $items as $itm ) {

					if( isset($date_arr) && sizeof($date_arr) > 0 ) {

						foreach( $date_arr as $dt ) {
						    if($cat_id == 'all') {
						        $user_cat = MenuTitle::where('created_by', $menu_owner)->lists('id');
                                //print_r($user_cat);exit;
                                $result = Purchase::join('menus', 'menus.id', '=', 'purchase.item_id')
                                    ->join('menu_titles', 'menu_titles.id', '=', 'menus.menu_title_id')
                                    ->join('invoice_bills', 'invoice_bills.id', '=', 'purchase.invoice_id')
                                    ->select('menus.item as item', 'purchase.rate as rate', 'purchase.quantity as qty', 'purchase.total as total')
                                    ->whereIn('menu_titles.id', $user_cat)
                                    ->where('invoice_bills.created_by', $owner_id)
                                    ->whereDate('invoice_bills.created_at', '=', $dt)
                                    ->where('menus.id', $itm->id)
                                    ->groupby('purchase.item_id')
                                    ->first();
                            }else{
                                $result = Purchase::join('menus', 'menus.id', '=', 'purchase.item_id')
                                    ->join('menu_titles', 'menu_titles.id', '=', 'menus.menu_title_id')
                                    ->join('invoice_bills', 'invoice_bills.id', '=', 'purchase.invoice_id')
                                    ->select('menus.item as item', 'purchase.rate as rate', 'purchase.quantity as qty', 'purchase.total as total')
                                    ->where('menu_titles.id', $cat_id)
                                    ->where('invoice_bills.created_by', $owner_id)
                                    ->whereDate('invoice_bills.created_at', '=', $dt)
                                    ->where('menus.id', $itm->id)
                                    ->groupby('purchase.item_id')
                                    ->first();
                            }

							if ( isset($result) && sizeof($result) > 0 ) {
								//print_r($result);exit;
								$data[$itm->id][$dt]['rate'] = $result->rate;
								$data[$itm->id][$dt]['qty'] = $result->qty;
								$data[$itm->id][$dt]['total'] = $result->total;
							} else {
								$data[$itm->id][$dt]['rate'] = 'NA';
								$data[$itm->id][$dt]['qty'] = 'NA';
								$data[$itm->id][$dt]['total'] = 'NA';
							}
						}
					}
					$itm_id_arr[] = $itm->id;
					$itm_name_arr[] = $itm->item;


				}
			}

			if ( $flag == 'statistic') {
				return view('report.purchaseRateReportList',array('data'=>$data,'dates'=>$date_arr,'items'=>$items,'show_qty'=>$show_qty,'show_total'=>$show_total,'flag'=>$flag));
			} else {

				$response['records'] = $data;
				$response['items'] = $items;
				$response['dates'] = $date_arr;
				$response['item_id'] = $itm_id_arr;
				$response['item_name'] = $itm_name_arr;
				return $response;
			}


		}

		$categories = MenuTitle::getOffSaleCategoriesDropdown($owner_id);
		if ( sizeof($categories) == 2 ) {
			unset($categories['']);
		}else{
			unset($categories['']);
			$categories['all'] = 'All Categories';
		}

		return view('report.purchaseRateReport',array('categories'=>$categories));

	}

	public function stockRequestReport(Request $request) {

        if ($request->ajax()) {
            $menu_owner = Owner::menuOwner();
            $locations = Location::where('created_by', $menu_owner)->get();

            $from_date = Input::get('from_date');
            $to_date = Input::get('to_date');

			Session::set('from_session',$from_date);
			Session::set('to_session',$to_date);

			$from_date = $from_date." 00:00:00";
			$to_date = $to_date." 23:59:59";

            $user_id = Input::get('user_id');
            $location_id = Input::get('location_id');
            $request_from = Input::get('request'); //app or web
            $data = array();

            $item_requests = ItemRequest::join('menus', 'menus.id', '=', 'item_request.what_item_id')
                ->join('unit', 'menus.unit_id', '=', 'unit.id')
                ->join('owners', 'owners.id', '=', 'item_request.owner_by')
                ->join('locations', 'locations.id', '=', 'item_request.location_for')
                ->whereBetween('item_request.when', array($from_date, $to_date))
				//->where('item_request.when','>=', $from_date)
				//->where('item_request.when','<=', $to_date)
                //  ->where('item_request.satisfied', '=', 'Yes')
                ->select('item_request.id as request_id','item_request.unit_id as unit_id','item_request.price as price',
                    'item_request.what_item_id', 'unit.name as unit_name', 'locations.name',
                    'locations.id as loc_id', 'owners.user_name',
                    'item_request.location_from', 'item_request.what_item',
                    'item_request.owner_to', 'item_request.owner_by','item_request.when',
                    DB::raw('ifnull(sum(item_request.qty),0) as requested_qty'),
                    'item_request.existing_qty', 'menus.id', 'menus.item','menus.secondary_units')
                ->groupBy('item_request.location_for')
                ->groupBy(DB::raw('DATE(item_request.when)'))
                ->groupBy('item_request.what_item_id')
                ->orderBy('item_request.location_for');

            if ($user_id == 'all' && $location_id == 'all') {
                $item_requests = $item_requests->get();
            } else if ($user_id == 'all') {
                $item_requests = $item_requests->where('item_request.location_for', $location_id)->get();
            } else if ($location_id == 'all') {
                $item_requests = $item_requests->where('item_request.owner_by', '=', $user_id)->get();
            } else {
                $item_requests = $item_requests->where('item_request.owner_by', '=', $user_id)
                    ->where('item_request.location_for', $location_id)->get();
            }

            /*$total_item_ids = ItemRequest::whereBetween('when', array($from_date, $to_date))
                ->groupBy('item_request.what_item_id')
                ->lists('what_item_id');*/

            $result = array();$loc_arr = array();
            $date_arr = $this->createDateRangeArray($from_date, $to_date);

            foreach ($locations as $loc) {
                /*foreach ($total_item_ids as $index => $req_item_id) {*/
                    foreach ($date_arr as $key => $val) {
                        foreach ($item_requests as $item_req) {
                            if (date('Y-m-d',strtotime($item_req->when)) == $val /*&& $req_item_id == $item_req->what_item_id*/ && $loc->id == $item_req->loc_id) {
                                if ( !array_key_exists($loc->id,$loc_arr)){
                                    $loc_arr[$loc->id]['id'] = $loc->id;
                                    $loc_arr[$loc->id]['name'] = $loc->name;
                                }
                                $result[$item_req->what_item_id][$loc->id][$val]['location'] = $item_req->name;
                                $result[$item_req->what_item_id][$loc->id][$val]['location_id'] = $item_req->loc_id;

                                $total_qty = $item_req->requested_qty;

                                //$item = Menu::find($item_req->what_item_id);

                                if( isset($item_req->secondary_units) && $item_req->secondary_units != '' ) {
                                    $units = json_decode($item_req->secondary_units);
                                    if ( isset($units) && $units != '' ) {
                                        foreach( $units as $key=>$u ) {
                                            if ( $key == $item_req->unit_id) {
                                                $total_qty = floatval($total_qty) * floatval($u);
                                            }
                                        }
                                    }

                                }

                                $result[$item_req->what_item_id][$loc->id][$val]['qty'] = $total_qty;
                                $result[$item_req->what_item_id][$loc->id][$val]['unit'] = $item_req->unit_name;
                                $result[$item_req->what_item_id][$loc->id][$val]['name'] = $item_req->what_item;
                                $result[$item_req->what_item_id][$loc->id][$val]['price'] = $item_req->price;
                            }
                        }
                    }
                /*}*/
            }

//print_r($loc_arr);exit;
            $data['request'] = $item_requests;
            $data['user_id'] = $user_id;
            $data['locations'] = $loc_arr;
            $data['result'] = $result;
            $data['dates'] = $date_arr;

            return view('report.stockResponseReportList',array('data'=>$data));
        }

        $hasOwner = Owner::hasCreatedBy();
        if ($hasOwner) {
            $users = Owner::where('id', Auth::id())->lists('user_name', 'id');
        } else {
            $users = Owner::where('created_by', Auth::id())->lists('user_name', 'id');
            $users[Auth::id()] = Auth::user()->user_name;
        }
        if (!$hasOwner){
            $users['all'] = 'All Users';
        }
        $locations = Location::where('created_by',Auth::id())->lists('name','id');
        $locations['all'] = 'All Locations';

//        if( preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"])){
//            return view('report.mobileStockRequest', array('users' => $users,'locations' => $locations));
//        } else {
            return view('report.stockRequestReport', array('users' => $users,'locations' => $locations));
//        }
        //return view('report.stockRequestReport',array('users' => $users,'locations' => $locations));
	}


	public function stockResponseReport(Request $request) {

		$menu_owner = Owner::menuOwner();
		$flag = Input::get('flag');

        if ($request->ajax() || ( isset($flag) && $flag == 'export')) {

			$locations = Location::where('created_by', $menu_owner)->get();

			$from_date = Input::get('from_date');
			$to_date = Input::get('to_date');

//echo $flag;exit;
			Session::set('from_session',$from_date);
			Session::set('to_session',$to_date);

			$from_date = $from_date." 00:00:00";
			$to_date = $to_date." 23:59:59";

			$user_id = Input::get('user_id');
			$location_id = Input::get('location_id');
			$request_from = Input::get('request'); //app or web
			$data = array();

			$item_requests = ItemRequest::join('unit', 'item_request.satisfied_unit_id', '=', 'unit.id')
				->join('owners', 'owners.id', '=', 'item_request.owner_by')
				->join('locations', 'locations.id', '=', 'item_request.location_for')
				->whereBetween('satisfied_when', array($from_date, $to_date))
				->where('item_request.satisfied', '=', 'Yes')
				->select('item_request.id as request_id','item_request.satisfied_unit_id as unit_id','item_request.price as price',
						'item_request.what_item_id', 'unit.name as unit_name', 'locations.name',
						'locations.id as loc_id', 'owners.user_name', 'item_request.satisfied',
						'item_request.location_from', 'item_request.what_item', 'item_request.satisfied_when',
						'item_request.owner_to', 'item_request.owner_by', 'item_request.when',
						DB::raw('ifnull(sum(item_request.statisfied_qty),0) as statisfied_qty'),
						'item_request.existing_qty')
				->groupBy('item_request.location_for')
				->groupBy(DB::raw('DATE(item_request.satisfied_when)'))
				->groupBy('item_request.what_item_id')
				->orderBy('item_request.location_for');

			if ($user_id == 'all' && $location_id == 'all') {
				$item_requests = $item_requests->get();
			} else if ($user_id == 'all') {
				$item_requests = $item_requests->where('item_request.location_for', $location_id)->get();
			} else if ($location_id == 'all') {
				$item_requests = $item_requests->where('item_request.owner_by', '=', $user_id)->get();
			} else {
				$item_requests = $item_requests->where('item_request.owner_by', '=', $user_id)
					->where('item_request.location_for', $location_id)->get();
			}

			/*$total_item_ids = ItemRequest::whereBetween('satisfied_when', array($from_date, $to_date))
				->where('item_request.satisfied', '=', 'Yes')
				->groupBy('item_request.what_item_id')
				->lists('what_item_id');*/

			$result = array();$loc_arr = array();
			$date_arr = $this->createDateRangeArray($from_date, $to_date);
			foreach ($locations as $loc) {
				/*foreach ($total_item_ids as $index => $req_item_id) {*/
					foreach ($date_arr as $key => $val) {
						foreach ($item_requests as $item_req) {
							if (date('Y-m-d',strtotime($item_req->satisfied_when)) == $val && /*$req_item_id == $item_req->what_item_id &&*/ $loc->id == $item_req->loc_id) {
								if ( !array_key_exists($loc->id,$loc_arr)){
									$loc_arr[$loc->id]['id'] = $loc->id;
									$loc_arr[$loc->id]['name'] = $loc->name;
								}
								$result[$item_req->what_item_id][$loc->id][$val]['location'] = $item_req->name;
								$result[$item_req->what_item_id][$loc->id][$val]['location_id'] = $item_req->loc_id;

								$total_qty = $item_req->statisfied_qty;

								$item = Menu::find($item_req->what_item_id);

								if( isset($item->secondary_units) && $item->secondary_units != '' ) {
									$units = json_decode($item->secondary_units);
									if ( isset($units) && $units != '' ) {
										foreach( $units as $key=>$u ) {
											if ( $key == $item_req->unit_id) {
												$total_qty = floatval($total_qty) * floatval($u);
											}
										}
									}

								}
								//print_r(!isset($result[$item_req->what_item_id][$loc->id][$val]['totalqty']));exit;
                                if(!isset($result[$item_req->what_item_id][$loc->id][$val]['totalqty'])){
                                    $result[$item_req->what_item_id][$loc->id][$val]['totalqty'] = 0;
                                }
                                //print_r($result[$item_req->what_item_id][$loc->id][$val]['totalqty']);exit;
								$result[$item_req->what_item_id][$loc->id][$val]['qty'] = $total_qty;
								$result[$item_req->what_item_id][$loc->id][$val]['unit'] = $item_req->unit_name;
								$result[$item_req->what_item_id][$loc->id][$val]['name'] = $item_req->what_item;
								$result[$item_req->what_item_id][$loc->id][$val]['price'] = $item_req->price;
                                $result[$item_req->what_item_id][$loc->id][$val]['totalqty'] += $total_qty;
							}
						}
					}
				/*}*/
			}

            $data['request'] = $item_requests;
            $data['user_id'] = $user_id;
            $data['locations'] = $loc_arr;
            $data['result'] = $result;
            $data['dates'] = $date_arr;


			$excel_name = 'Stock_Response_Report_from_'.$from_date.'_to_'.$to_date;
			if ( isset($flag) && $flag == 'export' ) {

				if ( isset($result) && sizeof($result) > 0 ) {

						Excel::create($excel_name, function($excel) use($data) {

							$excel->sheet('Sheet1', function($sheet) use($data) {

								$row = 1;
								foreach ( $data['locations'] as $loc ) {

									$cnt = 1;$col = 'A';$start_col = 'A';

									//set Loction Name
									foreach ( $data['dates'] as $dt ) {
										$col++;$col++;
									}
									$sheet->mergeCells($start_col.''.$row.':'.$col.''.$row);
									$loc_name = ucwords($loc['name']);
									$sheet->cell($start_col.''.$row, function($cell) use($loc_name) {

										// manipulate the cell
										$cell->setValue($loc_name);
										$cell->setAlignment('center');
										$cell->setValignment('center');
										$cell->setFontWeight('bold');

									});
									$sheet->setHeight($row, 50);

									$col = $start_col;$row++;
									//set dates title
									foreach ( $data['dates'] as $dt ) {

										if ( $cnt == 1 ) {
											$sheet->cell($col.''.$row, '');
											$col++;
										}

										$from = $col;
										$col++;
										$to = $col;

										$sheet->mergeCells($from.''.$row.':'.$to.''.$row);

										$sheet->cell($from.''.$row, function($cell) use($dt) {

											// manipulate the cell
											$cell->setValue($dt);
											$cell->setAlignment('center');
											$cell->setValignment('center');
											$cell->setFontWeight('bold');

										});
										//increment column to display dates
										$cnt++;$col++;
									}
									$sheet->setHeight($row, 40);

									$col = $start_col;$row++;$cnt = 1;
									//set column title
									foreach ( $data['dates'] as $dt ) {

										if ( $cnt == 1 ) {

											$sheet->cell($col.''.$row, function($cell) {

												$cell->setValue('Item');
												$cell->setAlignment('center');
												$cell->setValignment('center');
												$cell->setFontWeight('bold');

											});

											$col++;
										}

										$sheet->cell($col.''.$row, function($cell) {

											$cell->setValue('Qty');
											$cell->setAlignment('center');
											$cell->setValignment('center');
											$cell->setFontWeight('bold');

										});

										$col++;
										$sheet->cell($col.''.$row, function($cell) {

											$cell->setValue('Value');
											$cell->setAlignment('center');
											$cell->setValignment('center');
											$cell->setFontWeight('bold');

										});
										$price[$dt] = 0;
										//increment column to display dates
										$cnt++;$col++;
									}
                                    $sheet->cell($col.''.$row, function($cell) {

                                        $cell->setValue('Total Qty');
                                        $cell->setAlignment('center');
                                        $cell->setValignment('center');
                                        $cell->setFontWeight('bold');

                                    });
									$sheet->setHeight($row, 30);

									//set data in table
									$col = $start_col;$row++;$cnt = 1;

									foreach($data['result'] as $res_key=>$res_arr) {
										$i = 0;
										foreach($data['dates'] as $key=>$date) {

											//set item name
											if (isset($res_arr[$loc['id']][$date]) && $i == 0) {

												$i = 1;
												$itm_name = $res_arr[$loc['id']][$date]['name'];
												$sheet->cell($col . '' . $row, function ($cell) use ($itm_name) {

													$cell->setValue($itm_name);
													$cell->setAlignment('left');
													$cell->setValignment('center');
													$cell->setValignment('center');
													$cell->setFontWeight('bold');

												});

											}

										}

										$col++;

										if ( $i == 1 ) {
                                            $qtytotal = 0;
											//set Qty and value
											foreach( $data['dates'] as $key=>$date ) {

												if ( isset($res_arr[$loc['id']][$date]) ) {
                                                    $itm_name = $res_arr[$loc['id']][$date]['name'];
													//set qty
													$qty = $res_arr[$loc['id']][$date]['qty']." ".$res_arr[$loc['id']][$date]['unit'];
													$sheet->cell($col.''.$row, function($cell) use($qty){

														$cell->setValue($qty);
														$cell->setAlignment('right');
														$cell->setValignment('center');

													});

													//set value
													$col++;
													$value = $res_arr[$loc['id']][$date]['qty'] * $res_arr[$loc['id']][$date]['price'];
													$sheet->cell($col.''.$row, function($cell) use($value){

														$cell->setValue($value);
														$cell->setAlignment('right');
														$cell->setValignment('center');

													});

													$price[$date] += $res_arr[$loc['id']][$date]['qty'] * $res_arr[$loc['id']][$date]['price'];
                                                    $qtytotal += $res_arr[$loc['id']][$date]['qty'];

												} else {

													$sheet->cell($col.''.$row, function($cell) {

														$cell->setValue('-');
														$cell->setAlignment('center');
														$cell->setValignment('center');

													});
													$col++;
													$sheet->cell($col.''.$row, function($cell) {

														$cell->setValue('-');
														$cell->setAlignment('center');
														$cell->setValignment('center');

													});

												}

												$col++;

											}
                                            $sheet->cell($col.''.$row, function($cell) use($qtytotal){

                                                $cell->setValue($qtytotal);
                                                $cell->setAlignment('right');
                                                $cell->setValignment('center');
                                                $cell->setBackground('5FB37B');

                                            });

										}
										$sheet->setHeight($row, 20);
										$col = $start_col;
                                        if($i == 1){
                                            $row++;
                                        }
									}
                                    foreach( $data['dates'] as $key=>$date ) {
                                        $col++;$col++;
                                        $val = $price[$date];
                                        $sheet->cell($col.''.$row, function($cell) use($val){

                                            $cell->setValue($val);
                                            $cell->setAlignment('right');
                                            $cell->setValignment('center');
                                            $cell->setBackground('5FB37B');

                                        });
                                    }
									$row++;

								}
								$sheet->setAutoSize(true);

							});

						})->download('xls');
				}

			}

            return view('report.stockResponseReportList',array('data'=>$data));

        }

        $hasOwner = Owner::hasCreatedBy();
        if ($hasOwner) {
            $users = Owner::where('id', Auth::id())->lists('user_name', 'id');
        } else {
            $users = Owner::where('created_by', Auth::id())->lists('user_name', 'id');
            $users[Auth::id()] = Auth::user()->user_name;
        }

        if (!$hasOwner){
            $users['all'] = 'All Users';
        }
        $locations = Location::where('created_by',$menu_owner)->lists('name','id');
        $locations['all'] = 'All Locations';

        return view('report.stockResponseReport', array('users' => $users,'locations' => $locations));

	}

	public function closingStockReport(Request $request) {

		$admin = Owner::menuOwner();

		if ($request->ajax()) {

			$from_date = Input::get('from_date');
			$to_date = Input::get('to_date');

			Session::set('from_session',$from_date);
			Session::set('to_session',$to_date);

			$from_date = $from_date." 00:00:00";
			$to_date = $to_date." 23:59:59";

			$location_id = Input::get('location_id');

			$locations = Location::where('created_by',$admin)->get();
			$data = array();

			$item_requests = ItemRequest::join('unit', 'item_request.unit_id', '=', 'unit.id')
				->join('locations', 'locations.id', '=', 'item_request.location_for')
				->whereBetween('item_request.when', array($from_date, $to_date))
				->select('item_request.id as request_id','item_request.unit_id as unit_id',
					'item_request.what_item_id', 'unit.name as unit_name', 'locations.name as loc_name',
					'locations.id as loc_id', 'item_request.what_item','item_request.when',
					'item_request.existing_qty')
				->groupBy('item_request.location_for')
				->groupBy(DB::raw('DATE(item_request.when)'))
				->groupBy('item_request.what_item_id')
				->orderBy('item_request.location_for');


			if ($location_id == '') {
				$item_requests = $item_requests->get();
			} else {
				$item_requests = $item_requests->where('item_request.location_for', $location_id)->get();
			}

			$total_item_ids = ItemRequest::whereBetween('when', array($from_date, $to_date))
				->groupBy('item_request.what_item_id')
				->lists('what_item_id');

			$result = array();$loc_arr = array();
			$date_arr = $this->createDateRangeArray($from_date, $to_date);

			foreach ($locations as $loc) {

				foreach ($total_item_ids as $index => $req_item_id) {

					foreach ($date_arr as $key => $val) {

						foreach ($item_requests as $item_req) {

							if (date('Y-m-d',strtotime($item_req->when)) == $val && $req_item_id == $item_req->what_item_id && $loc->id == $item_req->loc_id) {

								if ( !array_key_exists($loc->id,$loc_arr)){
									$loc_arr[$loc->id]['id'] = $loc->id;
									$loc_arr[$loc->id]['name'] = $loc->name;
								}
								$result[$req_item_id][$loc->id][$val]['location'] = $item_req->loc_name;
								$result[$req_item_id][$loc->id][$val]['location_id'] = $item_req->loc_id;
								$result[$req_item_id][$loc->id][$val]['qty'] = $item_req->existing_qty;;
								$result[$req_item_id][$loc->id][$val]['unit'] = $item_req->unit_name;
								$result[$req_item_id][$loc->id][$val]['name'] = $item_req->what_item;
							}
						}
					}
				}
			}

			//print_r($result);exit;
			$data['request'] = $item_requests;
			$data['locations'] = $loc_arr;
			$data['result'] = $result;
			$data['dates'] = $date_arr;

			return view('report.closingStockReportList',array('data'=>$data));
		}
		$locations = Location::where('created_by',$admin)->lists('name','id');
		$locations[''] = 'All Location';


		return view('report.closingStockReport',array('locations' => $locations));
	}

	public function stockAgeingReport(Request $request) {

		$owner_id = Auth::id();

		if ($request->ajax()) {

			$from_date = Input::get('from_date');
			$to_date = Input::get('to_date');
			$location_id = Input::get('location_id');
			$item_id = Input::get('item_id');


		}

		$loc_arr = Location::where('created_by',$owner_id)->get();

		$locations['all'] = 'All Locations';
		if( isset($locations) && sizeof($locations) > 0 ) {
			foreach( $loc_arr as $loc ) {
				$locations[$loc->id] = $loc->name;
			}
		}

		$item_arr = Menu::where('is_sell',0)->where('expiry','>',0)->where('created_by',$owner_id)->get();

		$items['all'] = 'All Items';
		if( isset($item_arr) && sizeof($item_arr) > 0 ) {
			foreach( $item_arr as $itm ) {
				$items[$itm->id] = $itm->item;
			}
		}

		return view('report.stockAgeingReport',array('locations'=>$locations,'items'=>$items));
	}

    public function cancellationReport(Request $request){

        if ($request->ajax()) {

			$report_type = Input::get('report_type');
            $outlet_id = Session::get('outlet_session');

            $from_date = Input::get('from_date');
            $to_date = Input::get('to_date');

			Session::set('from_session',$from_date);
			Session::set('to_session',$to_date);

            //convert to session time
            $from = Utils::getSessionTime($from_date,'from');
            $to = Utils::getSessionTime($to_date,'to');

			if ( $report_type == 'order') {

				$orders = order_details::join('order_cancellation_mapper', 'order_cancellation_mapper.order_id', '=', 'orders.order_id')
					->leftjoin('order_items', 'order_items.order_id', '=', 'orders.order_id')
					->select('orders.*','order_cancellation_mapper.created_by as created_by','order_items.item_name as item_name', 'order_items.item_quantity as Quantity', 'order_cancellation_mapper.reason')
					->where('orders.table_end_date','>=', $from)
					->where('orders.table_end_date','<=', $to)
					->where('orders.outlet_id', '=', $outlet_id)
					->orderBy('orders.order_id', 'desc')
					->where('orders.cancelorder', "1")
					->get();

				//print_r($orders);exit;

				$itemlist=array();
				$data['orders']=array();
				foreach($orders as $order) {
					if (array_key_exists($order->order_id,$itemlist)) {
						if($order->Quantity>1){
							$itemlist[$order->order_id] = $itemlist[$order->order_id] . ",</br>" .$order->Quantity." x ".strtoupper($order->item_name);
						}else {
							$itemlist[$order->order_id] = $itemlist[$order->order_id] . ",</br>" ."1 x ".strtoupper($order->item_name);
						}
					} else {
						if($order->Quantity>1){
							$itemlist[$order->order_id] = $order->Quantity." x ".strtoupper($order->item_name);
						}else if($order->Quantity == 1) {
							$itemlist[$order->order_id] = "1 x ".strtoupper($order->item_name);
						}else{
                            $itemlist[$order->order_id] = "No Item Found.";
                        }

						array_push($data['orders'],$order);
					}
				}

				$data['itemlist']=$itemlist;
				$html = '';
				$html .= '<table border="1" id="disc_table" class="table data-tbl table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>T#</th>
                                <th>Order Type</th>
                                <th>Item Name</th>
                                <th>Reason</th>
                                <th>Amount</th>
                                <th>Discount Amount</th>
                                <th>Cancelled By</th>
                                <th>Cancel Date</th>
                            </tr>
                        </thead>
                        <tbody>';

				$total_price = 0;
				$total_simple_disc = 0;
				$total_noncharg_disc = 0;
				for($i=0;$i<sizeof($data['orders']);$i++){
					$total_price += $data['orders'][$i]->totalcost_afterdiscount;

					//get order cancelled by
					$created_by = Owner::where('id',$data['orders'][$i]->created_by)->first();

					if($data['orders'][$i]->totalcost_afterdiscount == $data['orders'][$i]->discount_value)
						$total_noncharg_disc += $data['orders'][$i]->discount_value;
					else
                        $discount_valueTotal = isset($data['orders'][$i]->discount_value) && !empty($data['orders'][$i]->discount_value) ? $data['orders'][$i]->discount_value : 0.00;

                        $Total = isset($data['orders'][$i]->item_discount_value) && !empty($data['orders'][$i]->item_discount_value) ? $data['orders'][$i]->item_discount_value : 0.00;
						$total_simple_disc += $discount_valueTotal;
					$html .= '<tr id="'.$data['orders'][$i]->order_id.'">
                                <td>'.$data['orders'][$i]->invoice_no.'</td>
                                <td>'.$data['orders'][$i]->table_no.'</td>
                                <td>'.ReportController::get_order_type($data['orders'][$i]->order_type).'</td>
                                <td>'.$data['itemlist'][ $data['orders'][$i]->order_id].'</td>
                                <td>'.$data['orders'][$i]->reason.'</td>
                                <td>'.$data['orders'][$i]->totalprice.'</td>
                                <td>'.number_format((float)($discount_valueTotal + $item_discount_valueTotal), 2).'</td>
                                <td>'.$created_by->user_name.'</td>
                                <td>'.Carbon::parse($data['orders'][$i]->table_end_date)->format('d-m-Y  h:i A').'</td>
                            </tr>';
				}

				$html .= '</tbody>
            	</table>';

				return $html;

			} else if ( $report_type == 'kot') {

				$kots = Kot::join('owners','owners.id','=','kot.created_by')
							->select('kot.*','owners.user_name as username')
							->where('kot.outlet_id',$outlet_id)
							->where('kot.updated_at','>=', $from)
							->where('kot.updated_at','<=', $to)
							->onlyTrashed()
							->get();


				$kt_arr = array();
				if ( isset($kots) && sizeof($kots) > 0 ) {
					$i = 0;
					foreach( $kots as $kt ) {
						$kt_arr[$i]['table_no'] = $kt->table_no;
						$kt_arr[$i]['item_name'] = urldecode($kt->item_name);
						$kt_arr[$i]['quantity'] = $kt->quantity;
						$kt_arr[$i]['price'] = $kt->price;
						$kt_arr[$i]['reason'] = urldecode($kt->reason);
						$kt_arr[$i]['username'] = $kt->username;
						$kt_arr[$i]['created_at'] = $kt->deleted_at;
						$i++;
					}

				}

				return view('report.cancelKotReport', array('kot'=>$kt_arr));

			}

        }

        return view('report.cancellationReport');

    }

	public function salesConsumptionReport(Request $request) {

		if ($request->ajax()) {

			$from_date = Input::get('from_date');
			$to_date = Input::get('to_date');
			$report_type = Input::get('report_type');
			$ot_id = Input::get('outlet_id');

			Session::set('from_session',$from_date);
			Session::set('to_session',$to_date);

			$sess_outlet_id = Session::get('outlet_session');

			if (isset($sess_outlet_id) && $sess_outlet_id != '') {
				$ot_id = $sess_outlet_id;
			}

			$param = array(
				'from_date'=>$from_date,
				'to_date'=>$to_date,
				'ot_id'=>$ot_id,
				'report_type'=>$report_type,
			);

			$httpclient = new HttpClientWrapper();
			$token = $_COOKIE['laravel_session'];

			//send Api call
			$report_detail = $httpclient->send_request('POST',$param,$_SERVER['SERVER_NAME'].'/api/v3/sales-consumption-report',$token);
			$result = json_decode($report_detail);
			//print_r($result);exit;
			return view('report.salesConsumptionReportList',array('result'=>$result));

		}

		$outlets = OutletMapper::getOutletsByOwnerId();

		if ( sizeof($outlets) == 2 ) {
			unset($outlets['']);
		}
		return view('report.salesConsumptionReport',array('outlets'=>$outlets));
	}

    public function cashSales(Request $request){

        if ($request->ajax()) {

            $sess_outlet_id = Session::get('outlet_session');
            $outlet_id = Input::get('outlet_id');

            if (isset($sess_outlet_id) && $sess_outlet_id != '') {
                $outlet_id = $sess_outlet_id;
            }

			$from_date = Input::get('from_date');
			$to_date = Input::get('to_date');

			Session::set('from_session',$from_date);
			Session::set('to_session',$to_date);

            //convert to session time
            $from = Utils::getSessionTime($from_date,'from');
            $to = Utils::getSessionTime($to_date,'to');

            $counter_close_amount = SendCloseCounterStatus::where('outlet_id','=',$outlet_id)
                                                            ->where('send_close_counter_status.start_date','>=', $from)
                                                            ->where('send_close_counter_status.close_date','<=', $to)
                                                            ->select('start_date','outlet_id','total_from_user','total_from_db')->get();

            return view('report.cash_salesList',array('cashsale'=>$counter_close_amount));

        }

        $outlets = OutletMapper::getOutletsByOwnerId();

        if ( sizeof($outlets) == 2 ) {
            unset($outlets['']);
        }

        return view('report.cash_sales', array('outlets'=>$outlets));
    }

	public function stockStatusReport(Request $request) {

		$httpclient = new HttpClientWrapper();
		$token = $_COOKIE['laravel_session'];

		if ($request->ajax()) {

			$from_date = Input::get('from_date');
			$to_date = Input::get('to_date');
			$loc_id = Input::get('location_id');
			$item_id = Input::get('item_id');
			$cat_id = Input::get('cat_id');


			$param = array(
				'from_date'=>$from_date,
				'to_date'=>$to_date,
				'cat_id'=>$cat_id,
				'item_id'=>$item_id,
				'loc_id'=>$loc_id,
				'flag'=>'list'
			);

			//send Api call
			$report_detail = $httpclient->send_request('POST',$param,$_SERVER['SERVER_NAME'].'/api/v3/stock-status-report',$token);
			$result = json_decode($report_detail);
			//print_r($result);exit;
			return view('report.stockStatusReportList',array('result'=>$result));

		}

		//send Api call
		$report_detail = $httpclient->send_request('GET',array('flag'=>'params'),$_SERVER['SERVER_NAME'].'/api/v3/stock-status-report',$token);
		$result = json_decode($report_detail);

		if ( isset($result) && $result->status == "success" ) {
			//print_r($result->result->categories);exit;
			return view('report.stockStatusReport',array('status'=>"success",'locations'=>$result->result->locations,'categories'=>$result->result->categories,'items'=>$result->result->items));
		} else {
			return view('report.stockStatusReport',array('status'=>"error"));
		}

	}

    public function getBarChartDateData(){

        $day = Input::get('day');

        $from = Carbon::now()->startOfDay()->subDays($day);
        $to = Carbon::now()->endOfDay()->subDays($day);

        //convert to session time
        $from = Utils::getSessionTime(date('Y-m-d',strtotime($from)),'from');
        $to = Utils::getSessionTime(date('Y-m-d',strtotime($to)),'to');

        //if outlet session set
        $outlet_id = Session::get('outlet_session');

        $items = array();

        if ( $outlet_id != '') {

            $items = OrderItem::join("orders", "orders.order_id", "=", "order_items.order_id")
                ->select('order_items.id','orders.table_start_date','order_items.item_id as order_items_id', "order_items.item_name as item", DB::raw('ifnull(sum(order_items.item_quantity),0) as count'))
                ->where('orders.table_end_date','>=', $from)
                ->where('orders.table_end_date','<=', $to)
                ->where('orders.outlet_id','=',$outlet_id)
                ->where('orders.cancelorder', '!=', 1)
                ->where('orders.invoice_no',"!=",'')
                ->groupBy('order_items.item_id')
                ->orderBy('count','desc')
                ->get();

        }

        $final_barchart_data = array();
        $i = 0;
        if(isset($items) && sizeof($items)>0) {
            foreach ($items as $item) {
                if ($i == 10) {
                    break;
                }

                if($item->order_items_id == 0){
                    $final_barchart_data[$i]['name'] = 'Open Item';
                    $final_barchart_data[$i++]['y'] = $item->count;
                }else {
                    $final_barchart_data[$i]['name'] = $item->item;
                    $final_barchart_data[$i++]['y'] = $item->count;
                }
            }
        }else{
            $final_barchart_data[$i]['name'] = 'No orders';
            $final_barchart_data[$i++]['y'] = 0;
        }

        $result['date'] = $final_barchart_data;

        return $final_barchart_data;

    }

    public function getBarChartMonthData(){

        $from = Carbon::yesterday()->startOfMonth();
        $to = Carbon::yesterday()->endOfDay();

        //convert to session time
        $from = Utils::getSessionTime(date('Y-m-d',strtotime($from)),'from');
        $to = Utils::getSessionTime(date('Y-m-d',strtotime($to)),'to');

        //if outlet session set
        $outlet_id = Session::get('outlet_session');

        $items = array();

        if ( $outlet_id != '') {

            $items = OrderItem::join("orders", "orders.order_id", "=", "order_items.order_id")
                                ->select('order_items.id','orders.table_start_date','order_items.item_id as order_items_id', "order_items.item_name as item", DB::raw('ifnull(sum(order_items.item_quantity),0) as count'))
                                ->where('orders.table_end_date','>=', $from)
                                ->where('orders.table_end_date','<=', $to)
                                ->where('orders.outlet_id','=',$outlet_id)
                                ->where('orders.cancelorder', '!=', 1)
                                ->where('orders.invoice_no',"!=",'')
                                ->groupBy('order_items.item_id')
                                ->orderBy('count','desc')
                                ->get();

        }

        $final_barchart_data = array();
        $i = 0;
        if(isset($items) && sizeof($items)>0) {
            foreach ($items as $item) {
                if ($i == 10) {
                    break;
                }
                if($item->order_items_id == 0) {
                    $final_barchart_data[$i]['name'] = "Open Item";
                    $final_barchart_data[$i++]['y'] = $item->count;
                }else{
                    $final_barchart_data[$i]['name'] = $item->item;
                    $final_barchart_data[$i++]['y'] = $item->count;
                }
            }
        }else{
            $final_barchart_data[$i]['name'] = 'No orders';
            $final_barchart_data[$i++]['y'] = 0;
        }

        $result['date'] = $final_barchart_data;

        return $final_barchart_data;

    }

	public function getPieChartData(){

        $from = Carbon::yesterday()->startOfMonth();
        $to = Carbon::yesterday()->endOfDay();

        //convert datetime to session datetime
        $from = Utils::getSessionTime(date('Y-m-d',strtotime($from)),'from');
        $to = Utils::getSessionTime(date('Y-m-d',strtotime($to)),'to');

        //if outlet session set
        $outlet_id = Session::get('outlet_session');

        $categories = array();

        if ( $outlet_id != '') {

            $categories = OrderItem::join("orders", "orders.order_id", "=", "order_items.order_id")
								->select("order_items.category_name as category", DB::raw('ifnull(sum(order_items.item_price*order_items.item_quantity),0) as count'))
                                ->where('orders.table_end_date', '>=', $from)
                                ->where('orders.table_end_date', '<=', $to)
								->where('orders.outlet_id', '=', $outlet_id)
								->where('orders.cancelorder', '!=', 1)
								->where('orders.invoice_no',"!=",'')
								->groupBy('order_items.category_id')
								->get();

        }

        $i = 0;
        $final_pichart_data = array();
        $result = array();

        foreach ($categories as $cat){
            $final_pichart_data[$i]['name'] = $cat->category;
            $final_pichart_data[$i++]['y'] = $cat->count;
        }

        if ( sizeof($final_pichart_data ) > 0 ) {
            $result['month_pie'] = $final_pichart_data;
        } else {
            $final_pichart_data[0]['name'] = 'No Order Found';
            $final_pichart_data[0]['y'] = 0;
            $result['month_pie'] = $final_pichart_data;
        }

        if(Carbon::now()->startOfDay() == Carbon::now()->startOfMonth()->startOfDay())
            $result['month_lbl'] = 'Previous';
        else
            $result['month_lbl'] = 'Current';

        return $result;
    }

    public function getPieChartDateData(){

        $day = Input::get('day');

        $to = Carbon::now()->endOfDay()->subDays($day);
        $from = Carbon::now()->startOfDay()->subDays($day);

        //convert to session time
        $from = Utils::getSessionTime(date('Y-m-d',strtotime($from)),'from');
        $to = Utils::getSessionTime(date('Y-m-d',strtotime($to)),'to');

        //if outlet session set
        $outlet_id = Session::get('outlet_session');

        $categories = array();

        if ( $outlet_id != '') {

            $categories = OrderItem::join("orders", "orders.order_id", "=", "order_items.order_id")
							->select("order_items.category_name as category", DB::raw('ifnull(sum(order_items.item_price*order_items.item_quantity),0) as count'))
                            ->where('orders.table_end_date', '>=', $from)
                            ->where('orders.table_end_date', '<=', $to)
							->where('orders.outlet_id', '=', $outlet_id)
							->where('orders.cancelorder', '!=', 1)
							->where('orders.invoice_no',"!=",'')
							->groupBy('order_items.category_id')
							->get();

        }

        $tot_items = 0;
        $i = 0;
        $final_pichart_data = array();
        $result = array();
        foreach ($categories as $cat){
            $tot_items += $cat->count;
        }
        foreach ($categories as $cat){
            $final_pichart_data[$i]['name'] = $cat->category;
            $final_pichart_data[$i++]['y'] = $cat->count;
        }
        if(sizeof($final_pichart_data)>0) {
            $result['date_pie'] = $final_pichart_data;
        }else{
            $final_pichart_data[0]['name'] = 'No Order Found';
            $final_pichart_data[0]['y'] = 0;
            $result['date_pie'] = $final_pichart_data;
        }

        $selected_date = Carbon::now()->subDays($day);

        $select_date = $selected_date->format('Y-m-d');
        $today = Carbon::now()->format('Y-m-d');
        if($select_date == $today){
            $lbl_date = 'Today '.$selected_date->format('(D)');;
        }else{
            $lbl_date = $selected_date->format('d M (D)');
        }
        $result['lbl'] = $lbl_date;
        $result['outlets']=array();

        $date_ord_pax = order_details::where('orders.outlet_id', $outlet_id)
            ->where('orders.table_end_date','>=', $from)
            ->where('orders.table_end_date','<=', $to)
            ->where('orders.cancelorder','!=','1')
            ->where('orders.invoice_no',"!=",'')
            ->get();

        $date_ord = sizeof($date_ord_pax);
        $date_person = 0; $date_revenue = 0;

        foreach ($date_ord_pax as $order){
            $date_person += $order->person_no;
            $date_revenue += $order->totalprice;
        }

        $result['date_person'] = $date_person;
        $result['date_order'] = $date_ord;
        $result['date_revenue'] = number_format($date_revenue,3);

		$sod = date('Y-m-d',strtotime($from));
		//echo $sod;exit;
        $date_expense = Expense::where('expense_for', $outlet_id )
            ->whereRaw("(status= 'verified' || status = 'paid') && expense_date = '$sod'")
			->where('type','expense')
            ->sum('amount');

        $result['date_expense'] = number_format($date_expense,3);
        $result['last_updated'] = date('Y-m-d H:i:s');
        $result['ongoing_tables'] = "";
        if($day == 0) {

            $ongoing_kot = Kot::where('status', 'open')
                ->selectRaw('sum( price * quantity ) as ongoing')
                ->where('kot_time', '>=', $from)
                ->where('kot_time', '<=', $to)
                ->where('outlet_id', $outlet_id)
                ->groupBy('status')
                ->get();

            if (sizeof($ongoing_kot) > 0) {
                $result['ongoing_tables'] = "(" . number_format($ongoing_kot[0]->ongoing, 3) . ")";
            }
        }

        return $result;


    }

	//response deviation report
	public function responseDeviation(Request $request) {

		$admin_id = Owner::menuOwner();

		if ($request->ajax()) {

			$loc_id = Input::get('location_id');

			$from_date = Input::get('from_date');
			$to_date = Input::get('to_date');

			Session::set('from_session',$from_date);
			Session::set('to_session',$to_date);

			$from = $from_date." 00:00:00";
			$to = $to_date." 23:59:59";

			$response_items = ResponseDeviation::join('unit as req_unit','req_unit.id','=','response_deviation.request_unit_id')
												->join('unit as res_unit','res_unit.id','=','response_deviation.satisfied_unit_id')
												->join('locations as l','l.id','=','response_deviation.from_location_id')
												->join('owners as o','o.id','=','response_deviation.satisfied_by')
												->select('response_deviation.*','o.user_name as owner','l.name as from_location','req_unit.name as req_unit','res_unit.name as res_unit')
												->where('for_location_id',$loc_id)
												->where('response_deviation.satisfied_when', '<=', $to)
												->where('response_deviation.satisfied_when', '>=', $from)
												->orderby('response_deviation.satisfied_when')
												->get();

			$zero_qty = $less_qty = $more_qty = array();
			$i = $j = $k = 0;
			if ( isset($response_items) && sizeof($response_items) > 0 ) {
				foreach ( $response_items as $itm ) {

					if ( $itm->satisfied_qty == 0 ) {

						$zero_qty[$i]['item_name'] = $itm->item_name;
						$zero_qty[$i]['req_qty'] = $itm->request_qty.''.$itm->req_unit;
						$zero_qty[$i]['res_qty'] = $itm->satisfied_qty.''.$itm->res_unit;
						$zero_qty[$i]['satisfied_by'] = $itm->owner;
						$zero_qty[$i]['from_location'] = $itm->from_location;
						$zero_qty[$i]['satisfied_date'] = date('d-m-Y H:i:s',strtotime($itm->satisfied_when));

						$i++;

					} else if ( $itm->satisfied_qty < $itm->request_qty ) {

						$less_qty[$j]['item_name'] = $itm->item_name;
						$less_qty[$j]['req_qty'] = $itm->request_qty.''.$itm->req_unit;
						$less_qty[$j]['res_qty'] = $itm->satisfied_qty.''.$itm->res_unit;
						$less_qty[$j]['satisfied_by'] = $itm->owner;
						$less_qty[$j]['from_location'] = $itm->from_location;
						$less_qty[$j]['satisfied_date'] = date('d-m-Y H:i:s',strtotime($itm->satisfied_when));

						$j++;

					} else if ( $itm->satisfied_qty > $itm->request_qty ) {

						$more_qty[$k]['item_name'] = $itm->item_name;
						$more_qty[$k]['req_qty'] = $itm->request_qty.''.$itm->req_unit;
						$more_qty[$k]['res_qty'] = $itm->satisfied_qty.''.$itm->res_unit;
						$more_qty[$k]['satisfied_by'] = $itm->owner;
						$more_qty[$k]['from_location'] = $itm->from_location;
						$more_qty[$k]['satisfied_date'] = date('d-m-Y H:i:s',strtotime($itm->satisfied_when));

						$k++;

					}

				}
			}

			return view('report.responseDeviationList',array('zero_qty'=>$zero_qty,'more_qty'=>$more_qty,'less_qty'=>$less_qty));
		}

		$locations = Location::getLocations($admin_id);
		$locations[''] = 'Select For Location';

		return view('report.responseDeviation',array('locations'=>$locations));

	}

	public function campaignReport(Request $request) {

		if ($request->ajax())
		{
			$input = Input::all();
			$response = array();

			$search = $input['sSearch'];

			$sort = $input['sSortDir_0'];
			$sortCol=$input['iSortCol_0'];
			$sortColName=$input['mDataProp_'.$sortCol];

			$sort_field = 'stocks.updated_at';
			//echo $sort_field;exit;
			//sort by column
			if ( $sortColName == "owner_name" ) {
				$sort_field = 'campaign.owner_name';
			} elseif ( $sortColName == "outlet_name" ) {
				$sort_field = 'campaign.outlet_name';
			} elseif ( $sortColName == "updated_at" ) {
				$sort_field = 'campaign.updated_at';
			} elseif ( $sortColName == "verified" ) {
				$sort_field = 'campaign.verified';
			} else {
				$sort_field = 'campaign.updated_at';
				$sort = 'DESC';
			}

			$total_colomns = $input['iColumns'];
			$search_col = '';$query_filter = '';

			for ( $j=0; $j<=$total_colomns-1; $j++ ) {

				if ( $j == 0 )continue;

				if ( isset($input['sSearch_'.$j]) && $input['sSearch_'.$j] != '' ) {

					$search = $input['sSearch_'.$j];
					$searchColName = $input['mDataProp_'.$j];
					//echo $searchColName;exit();

					if ( $searchColName == 'owner_name' ) {

						if ( isset($search_col) && $search_col != '' ) {
							$search_col .= " AND campaign.owner_name like '%$search%'";
						} else {
							$search_col = "campaign.owner_name like '%$search%'";
						}

					} else if ( $searchColName == 'mobile' ) {

						if ( isset($search_col) && $search_col != '' ) {
							$search_col .= " AND campaign.mobile = '$search'";
						} else {
							$search_col = "campaign.mobile = '$search'";
						}

					} else if ( $searchColName ==  'outlet_name' ) {

						if ( isset($search_col) && $search_col != '' ) {
							$search_col .= " AND campaign.outlet_name like '%$search%'";
						} else {
							$search_col = "campaign.outlet_name like '%$search%'";
						}

					} else if ( $searchColName ==  'email' ) {

						if ( isset($search_col) && $search_col != '' ) {
							$search_col .= " AND campaign.email = '$search'";
						} else {
							$search_col = "campaign.email = '$search'";
						}

					} else if ( $searchColName ==  'verified' ) {

						if ( isset($search_col) && $search_col != '' ) {
							$search_col .= " AND campaign.verified = '$search'";
						} else {
							$search_col = "campaign.verified = '$search'";
						}

					} else if ( $searchColName ==  'address' ) {

						if ( isset($search_col) && $search_col != '' ) {
							$search_col .= " AND campaign.address like '%$search%'";
						} else {
							$search_col = "campaign.address like '%$search%'";
						}

					} else if ( $searchColName ==  'updated_at' ) {
						//echo 'here';exit;
						$from = $search." 00:00:00";
						$to = $search." 23:59:59";

						if ( isset($search_col) && $search_col != '' ) {
							$search_col .= " AND campaign.updated_at BETWEEN '$from' AND '$to'";
						} else {
							$search_col = "stocks.updated_at BETWEEN '$from' AND '$to'";
						}

					}

				}

			}

			//echo $search_col;exit;

			if ( $search_col == '')$search_col = '1=1';

			$where = '1=1 AND ';

			$total_records = Campaign::whereRaw(" $where ($search_col)")->count();

			$campaign_result = Campaign::whereRaw(" $where ($search_col)")
				->take($input['iDisplayLength'])
				->skip($input['iDisplayStart'])
				->orderBy($sort_field, $sort)->get();


			if ( $total_records > 0 ) {

				$i = 0;
				foreach ( $campaign_result as $camp ) {

					$response['result'][$i]['DT_RowId'] = $camp->id;
					$response['result'][$i]['check_col'] = "";
					$response['result'][$i]['email'] = $camp->email;
					$response['result'][$i]['mobile'] = $camp->mobile;
					$response['result'][$i]['address'] = $camp->address;
					$response['result'][$i]['outlet_name'] = $camp->outlet_name;
					$response['result'][$i]['owner_name'] = $camp->owner_name;
					if ( $camp->verified == 1 ) {
						$status = 'Verified';
					} else {
						$status = 'Unverified';
					}
					$response['result'][$i]['verified'] = $status;
					$response['result'][$i]['updated_at'] = date('Y-m-d h:i a',strtotime($camp->updated_at));
					$response['result'][$i]['get_image'] = '<a href="#" onclick="getImage('.$camp->id.')">Show Images</a>';

					$i++;
				}


			} else {
				$total_records = 0;
				$response['result'] = array();
			}

			$response['iTotalRecords'] = $total_records;
			$response['iTotalDisplayRecords'] = $total_records;
			$response['aaData'] = $response['result'];

			return json_encode($response);
		}

		return view('report.campaignReport');

	}

	public function paymentReport(Request $request) {

		if ($request->ajax()) {

			$ot_id = Input::get('ot_id');
			$from = Input::get('from_date');
			$to = Input::get('to_date');

			$pay_opt = PaymentOption::where('name','Online')->first();
			$source = Sources::where('name','UPI')->first();

			$result = order_details::join('icici_upi_transaction as icici','orders.order_unique_id','=','icici.bill_no')
									->join('outlets as ot','ot.id','=','orders.outlet_id')
									->select('orders.*','ot.name as outlet_name','icici.status as upi_status')
									->where('orders.invoice_no',"!=",'')
									->where('orders.table_end_date','>=', (new Carbon($from))->startOfDay())
									->where('orders.table_end_date','<=', (new Carbon($to))->endOfDay())
									->where('orders.payment_option_id',isset($pay_opt->id)?$pay_opt->id:'')
									->where('orders.source_id',isset($source->id)?$source->id:'')
									->orderBy('orders.order_id', 'desc')
									->where('orders.cancelorder', '!=', 1);

			if ( $ot_id == 'all') {
				$orders = $result->get();
			} else {
				$orders = $result->where('orders.outlet_id',$ot_id)->get();
			}

			//print_r($orders);exit;

			return view('report.paymentReportList',array('orders'=>$orders,'outlet_id'=>$ot_id));
		}

		$outlets = Outlet::all()->lists('name','id');
		$outlets['all'] = 'All';
		return view('report.paymentReport',array('outlets'=>$outlets));
	}

	//get unpaid order by mobile no. and room no.
	public function unpaidOrdersReport( Request $request ) {

		$outlet_id = Session::get('outlet_session');

		if ($request->ajax()) {

			$from_date = Input::get('from_date');
			$to_date = Input::get('to_date');

            //convert to session time
            $from = Utils::getSessionTime($from_date,'from');
            $to = Utils::getSessionTime($to_date,'to');

			$mobile = Input::get('mobile');
			$table_no = Input::get('table_no');

			Session::set('from_session',$from_date);
			Session::set('to_session',$to_date);

			$where = '1=1';
			$result = order_details::join('order_payment_modes as opm','opm.order_id','=','orders.order_id')
                                    ->where('orders.table_end_date','>=', $from)
									->where('orders.table_end_date','<=', $to)
                                    ->where('opm.payment_option_id',0)
									->where('orders.outlet_id',$outlet_id)
                                    ->where('orders.invoice_no',"!=",'')
                                    ->where('orders.cancelorder', '!=', 1)
                                    ->orderBy('orders.order_id', 'desc');

			if ( isset($mobile) && $mobile != '' ) {
				$where .= " && user_mobile_number = " . $mobile;
			}

			if ( isset($table_no) && $table_no != '' ) {
				$where .= " && table_no = " . $table_no;
			}

			$orders = $result->whereRaw($where)->get();

			$list = array();

			if ( isset($orders) && sizeof($orders) > 0 ) {
				$i = 0;
				foreach ( $orders as $ord ) {

                    $list[$i]['id'] = $ord->order_id;
                    $list[$i]['inv_no'] = $ord->invoice_no;
                    $list[$i]['total'] = number_format($ord->amount,2);
                    $list[$i]['date'] = $ord->table_end_date;
                    $i++;
				}
			}

			return view('report.unpaidOrdersList',array('list'=>$list));

		}

		$tables = Tables::where('outlet_id',$outlet_id)->get();
		$lable = Outlet::find($outlet_id)->order_lable;

		//payment options
		$pay_option = PaymentOption::getOutletPaymentOption($outlet_id);

		return view('report.unpaidOrders',array('tables'=>$tables,'lable'=>$lable,'pay_option'=>$pay_option));

	}

	public function saleReport( Request $request ) {

		$outlet_id = Session::get('outlet_session');
		$flag = Input::get('flag');

		if ( $request->ajax() || ( isset($flag) && $flag == 'export')) {

			$from_date = Input::get('from_date');
			$to_date = Input::get('to_date');

			Session::set('from_session',$from_date);
			Session::set('to_session',$to_date);

            //convert to session time
            $from = Utils::getSessionTime($from_date,'from');
            $to = Utils::getSessionTime($to_date,'to');

			$orders = order_details::leftjoin("order_items","order_items.order_id","=","orders.order_id")
				->leftjoin("menus as m","m.id","=","order_items.item_id")
				->leftjoin("order_payment_modes as opm","opm.order_id","=","orders.order_id")
				->select('orders.*','opm.payment_option_id as poi','opm.source_id as si','m.item_code as item_code','order_items.item_quantity as Quantity',
						'order_items.item_name as item_name','order_items.item_quantity as qty','order_items.item_price as item_price',
					'order_items.category_name as category_name')
				->where('orders.table_end_date','>=', $from)
				->where('orders.table_end_date','<=', $to)
				->where('orders.outlet_id','=',$outlet_id)
				->orderBy('orders.order_id', 'desc')
				->where('orders.cancelorder', '!=', 1)
				->where('orders.invoice_no', '!=','')
				->get();


			$data = array(); $i = 0; $total = 0;
			if ( isset($orders) && sizeof($orders) > 0 ) {

				foreach ( $orders as $ord ) {

					$data[$i]['Invoice No'] = $ord->invoice_no;
					$data[$i]['Date'] = isset($ord->table_end_date)?date("d-m-Y", strtotime($ord->table_end_date)):"Table not closed";
					$data[$i]['Item Code'] = $ord->item_code;
					$data[$i]['Item Name'] = $ord->item_name;
                    $data[$i]['Quantity'] = $ord->qty;
					$data[$i]['Price'] = number_format($ord->item_price,2);
                    $data[$i]['Total'] = number_format($ord->item_price*$ord->qty,2);
					$data[$i]['Category'] = $ord->category_name;

                    //get payment mode
                    $pay_mode = 'UnPaid';
                    if( $ord->poi != 0 ) {
                        $mode = PaymentOption::find($ord->poi);
                        if ( isset($mode) && !empty($mode) ) {
                            $pay_mode = $mode->name;
                        }

                        if ( $ord->si != 0 ) {
                            $src = Sources::find($ord->si);
                            if ( isset($src) && sizeof($src) > 0 ) {
                                $pay_mode .=" - ".$src->name;
                            }
                            //$pay_mode .=" - ".$src->name;
                        }
                    }
                    $data[$i]['Payment Mode'] = $pay_mode;

					$total += $ord->item_price*$ord->qty;
					$i++;

				}
			}


			if ( isset($flag) && $flag == 'export') {

				$excel_name = 'Sales_Report_from_'.$from_date."_to_".$to_date;

				Excel::create($excel_name, function($excel) use($data,$total) {

					$excel->sheet('Sheet1', function($sheet) use($data) {

						$sheet->cells('A1:H1', function ($cells){
							$cells->setBackground('#E04833');
							$cells->setFontColor('#ffffff');

						});


						$sheet->cells('E:G', function ($cells){
							$cells->setAlignment('right');
						});

						$sheet->setOrientation('landscape');
						$sheet->fromArray($data);
						//$sheet->setAutoSize(true);
					});

					$last_row = $excel->getActiveSheet()->getHighestRow() + 1;

					$excel->getActiveSheet()->cells('A'.$last_row, function ($cells){
						$cells->setValue('Total');
					});
					$excel->getActiveSheet()->cells('G'.$last_row, function ($cells) use($total){
						$cells->setValue(number_format($total,2));
					});

					$excel->getActiveSheet()->cells('A'.$last_row.':H'.$last_row, function ($cells){
						$cells->setBackground('#BEFF33');
						$cells->setFontWeight('bold');
					});

					$excel->getActiveSheet()->getStyle('E1:E'.$excel->getActiveSheet()->getHighestRow())
						->getAlignment()->setWrapText(true);

					foreach($excel->getActiveSheet()->getRowDimensions() as $rd) {
						$rd->setRowHeight(-1);

					}

				})->download('xls');

			}
			$size = sizeof($data);
			return view('report.salesReportList',array('data'=>$data,'size'=>$size,'grandTotal'=>$total));

		}

		return view('report.saleReport');
	}

    public function duplicateInvoicenoReport( Request $request ) {

        if ( $request->ajax() ) {

            $from_date = Input::get('from_date');
            $to_date = Input::get('to_date');
            $outlet_id = Input::get('outlet_id');

            Session::set('from_session',$from_date);
            Session::set('to_session',$to_date);

            $orders = order_details::join("outlets", "outlets.id", "=", "orders.outlet_id")
                            ->select('invoice_no',DB::raw('count(*) as record'))
                            ->where('orders.table_end_date', '>=', (new Carbon($from_date))->startOfDay())
                            ->where('orders.table_end_date', '<=', (new Carbon($to_date))->endOfDay())
                            ->where('orders.invoice_no', '!=', '')
                            ->groupby('invoice_no')
                            ->orderby('orders.table_end_date','DESC')
                            ->having('record', '>', 1);

            if ($outlet_id == 'all') {
                $result = $orders->get();
            } else {
                $result = $orders->where('orders.outlet_id', '=', $outlet_id)->get();
            }

            $data = array(); $i = 0; $total = 0;
            if ( isset($result) && sizeof($result) > 0 ) {

                foreach ( $result as $ord ) {

                    $dup_inv = order_details::join("outlets", "outlets.id", "=", "orders.outlet_id")
                                            ->select('invoice_no','outlets.name as name','table_end_date as date')
                                            ->where('invoice_no',$ord->invoice_no);

                    if ($outlet_id == 'all') {
                        $result1 = $dup_inv->get();
                    } else {
                        $result1 = $dup_inv->where('orders.outlet_id', '=', $outlet_id)->get();
                    }


                    if ( isset($result1) && sizeof($result1) > 0 ) {
                        foreach ( $result1 as $inv ) {

                            $data[$i]['inv_no'] = $inv->invoice_no;
                            $data[$i]['date'] = $inv->date;
                            $data[$i]['ot_name'] = $inv->name;
                            $i++;

                        }
                    }

                }
            }

            $size = sizeof($data);
            return view('report.duplicateInvoiceNoList',array('data'=>$data,'size'=>$size));

        }

        $outlets = Outlet::all()->lists('name','id');
        $outlets['all'] = 'All';

        return view('report.duplicateInvoiceNo',array('outlets'=>$outlets));

    }

    public function kotvsordersdiff(Request $request){

        if ($request->ajax()) {

            $outlet_id = Input::get('outlet_id');

            $from_date = Input::get('from_date');
            $to_date = Input::get('to_date');

            Session::set('from_session',$from_date);
            Session::set('to_session',$to_date);


            $from_date = Carbon::parse(date('Y-m-d', strtotime($from_date )))->startOfDay();
            $to_date = Carbon::parse(date('Y-m-d', strtotime($to_date)))->endOfDay();
            $notmatch = Kot::kotOrderDiff($outlet_id, $from_date, $to_date);

            return view('report.kotsubtotaldiffList',array('kotsubtotaldiff'=>$notmatch));

        }

        $outlets = Outlet::where("active","Yes")->lists("name","id");
        $outlets[""] = "Select Outlet";


        return view('report.kotSubTotalDiffReport', array('outlets'=>$outlets));
    }

    public function zohoUnsyncOrdersReport(Request $request) {

        if ($request->ajax()) {

            $outlet_id = Session::get('outlet_session');

            $from_date = Input::get('from_date');
            $to_date = Input::get('to_date');
            $flag = Input::get('flag');

            Session::set('from_session',$from_date);
            Session::set('to_session',$to_date);

            //convert to session time
            $from = Utils::getSessionTime($from_date,'from');
            $to = Utils::getSessionTime($to_date,'to');

            if ( $flag == 'show' ) {

                $orders = order_details::where('orders.table_end_date', '>=', $from)
                    ->where('orders.table_end_date', '<=', $to)
                    ->where('orders.outlet_id', '=', $outlet_id)
                    ->orderBy('orders.order_id', 'desc')
                    ->where('orders.cancelorder', '!=', 1)
                    ->where('orders.invoice_no', '!=', '')
                    ->where('orders.zoho_sync', '!=', 1)
                    ->orderBy('orders.order_id', 'desc')
                    ->get();


                return view('report.zohoUnsyncOrdersList',array('orders'=>$orders));

            } else {

                order_details::syncZohoOrders($outlet_id, $from, $to);
                echo 'success';exit;
            }

        }
        return view('report.zohoUnsyncOrders');
    }
}
