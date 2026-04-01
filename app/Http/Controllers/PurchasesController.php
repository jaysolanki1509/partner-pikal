<?php
namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\InvalidPurchaseImport;
use App\InvoiceBill;
use App\Location;
use App\Menu;
use App\Outlet;
use App\OutletMapper;
use App\Owner;
use App\Purchase;
use App\Stock;
use App\StockAge;
use App\StockHistory;
use App\Unit;
use App\Vendor;
use Aws\CloudFront\Exception\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use phpDocumentor\Reflection\Types\Object_;
class PurchasesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['home']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $owner_id = Auth::id();
        if ($request->ajax()) {
            $input = Input::all();
            $response = array();
            $search = $input['sSearch'];
            $sort = $input['sSortDir_0'];
            $sortCol = $input['iSortCol_0'];
            $sortColName = $input['mDataProp_' . $sortCol];
            $sort_field = 'invoice_bills.invoice_date';
            //echo $sort_field;exit;
            //sort by column
            if ($sortColName == "invoice_no") {
                $sort_field = 'invoice_bills.invoice_no';
            }
            elseif ($sortColName == "vendor") {
                $sort_field = 'vendors.name';
            }
            elseif ($sortColName == "location") {
                $sort_field = 'locations.name';
            }
            elseif ($sortColName == "status") {
                $sort_field = 'invoice_bills.status';
            }
            elseif ($sortColName == "invoice_date") {
                $sort_field = 'invoice_bills.invoice_date';
            }
            else {
                $sort_field = 'invoice_bills.invoice_date';
                $sort = 'DESC';
            }
            $total_colomns = $input['iColumns'];
            $search_col = '';
            $query_filter = '';
            for ($j = 0; $j <= $total_colomns; $j++) {
                //if ( $j == 0 )continue;
                if (isset($input['sSearch_' . $j]) && $input['sSearch_' . $j] != '') {
                    $search = $input['sSearch_' . $j];
                    $searchColName = $input['mDataProp_' . ($j - 1)];
                    //echo $searchColName;
                    if ($searchColName == 'invoice_no') {
                        if (isset($search_col) && $search_col != '') {
                            $search_col .= " AND invoice_bills.invoice_no like '%$search%'";
                        }
                        else {
                            $search_col = "invoice_bills.invoice_no like '%$search%'";
                        }
                    }
                    else if ($searchColName == 'vendor') {
                        if (isset($search_col) && $search_col != '') {
                            $search_col .= " AND vendors.name like '%$search%'";
                        }
                        else {
                            $search_col = "vendors.name like '%$search%'";
                        }
                    }
                    else if ($searchColName == 'status') {
                        if (isset($search_col) && $search_col != '') {
                            $search_col .= " AND invoice_bills.status = '$search'";
                        }
                        else {
                            $search_col = "invoice_bills.status = '$search'";
                        }
                    }
                    else if ($searchColName == 'location') {
                        if (isset($search_col) && $search_col != '') {
                            $search_col .= " AND locations.id = '$search'";
                        }
                        else {
                            $search_col = "locations.id ='$search'";
                        }
                    }
                    else if ($searchColName == 'invoice_date') {
                        //echo 'here';exit;
                        $from = $search . " 00:00:00";
                        $to = $search . " 23:59:59";
                        if (isset($search_col) && $search_col != '') {
                            $search_col .= " AND invoice_bills.invoice_date like '$search'";
                        }
                        else {
                            $search_col = "invoice_bills.invoice_date like '$search'";
                        }
                    }
                }
            }
            //echo $search_col;exit;
            if ($search_col == '')
                $search_col = '1=1';
            $where = 'invoice_bills.created_by =' . $owner_id . ' AND ';
            $total_records = InvoiceBill::leftjoin('vendors', 'vendors.id', '=', 'invoice_bills.vendor_id')
                ->leftjoin('locations', 'locations.id', '=', 'invoice_bills.location_id')
                ->select('invoice_bills.*', 'vendors.name as vendor', 'locations.name as location')
                ->whereRaw(" $where ($search_col)")
                ->where('invoice_bills.total', ">", "0")
                ->count();
            $invoice_result = InvoiceBill::leftjoin('vendors', 'vendors.id', '=', 'invoice_bills.vendor_id')
                ->leftjoin('locations', 'locations.id', '=', 'invoice_bills.location_id')
                ->select('invoice_bills.*', 'vendors.name as vendor', 'locations.name as location')
                ->whereRaw(" $where ($search_col)")
                ->where('invoice_bills.total', ">", "0")
                ->take($input['iDisplayLength'])
                ->skip($input['iDisplayStart'])
                ->orderBy($sort_field, $sort)
                ->orderBy('invoice_bills.id', 'desc')->get();
            if ($total_records > 0) {
                $i = 0;
                foreach ($invoice_result as $inv) {
                    $response['result'][$i]['DT_RowId'] = $inv->id;
                    $response['result'][$i]['check_col'] = "<input class='checkbox1' name='sel_box' id='$inv->id' onclick=selectRow('$inv->id') type='checkbox' />";
                    $response['result'][$i]['invoice_no'] = $inv->invoice_no;
                    $response['result'][$i]['vendor'] = $inv->vendor;
                    $response['result'][$i]['location'] = $inv->location;
                    $response['result'][$i]['invoice_date'] = $inv->invoice_date;
                    $response['result'][$i]['status'] = ucfirst($inv->status);
                    $response['result'][$i]['action'] = '<a href="/purchase/' . $inv->id . '/edit" title="Edit"><span class="zmdi zmdi-edit" ></span></a>' . '&nbsp;&nbsp;&nbsp;&nbsp;<a onclick="warn(this,' . $inv->id . ')"href="#" title="Delete"><span class="zmdi zmdi-close"></span></a>';
                    $i++;
                }
            }
            else {
                $total_records = 0;
                $response['result'] = array();
            }
            $response['iTotalRecords'] = $total_records;
            $response['iTotalDisplayRecords'] = $total_records;
            $response['aaData'] = $response['result'];
            $locations = Location::where('created_by', $owner_id)->get();
            $response['locations'] = $locations;
            //print_r($response);exit;
            return json_encode($response);
        }
        $admin_id = Owner::menuOwner();
        $outlet_id = Session::get('outlet_session');
        $selected_location = "";
        /*location array*/
        $locations = array('' => 'Select Location');
        $outlet_list = OutletMapper::getOutletIdByOwnerId($owner_id);
        foreach ($outlet_list as $outlet) {
            $locations_list = Location::where('outlet_id', $outlet->outlet_id)->get();
            if (isset($locations_list) && !empty($locations_list)) {
                foreach ($locations_list as $loc) {
                    if ($outlet->outlet_id == $outlet_id && $loc->default_location == 1) {
                        $selected_location = $loc->id;
                    }
                    $locations[$loc->id] = $loc->name;
                }
            }
        }
        $vendors = Vendor::where('created_by', $admin_id)->get();
        $vendor_list = array();
        foreach ($vendors as $vendor) {
            $vendor_list[$vendor->id] = $vendor->name;
        }
        return view('purchases.index', array('locations' => $locations, 'selected_location' => $selected_location, 'vendors' => $vendor_list));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $owner_id = Auth::id();
        $admin_id = Owner::menuOwner();
        $outlet_id = Session::get('outlet_session');
        $selected_location = "";
        /*location array*/
        $locations = array('' => 'Select Location');
        $outlet_list = OutletMapper::getOutletIdByOwnerId($owner_id);
        foreach ($outlet_list as $outlet) {
            $locations_list = Location::where('outlet_id', $outlet->outlet_id)->get();
            if (isset($locations_list) && !empty($locations_list) ) {
                foreach ($locations_list as $loc) {
                    if ($outlet->outlet_id == $outlet_id && $loc->default_location == 1) {
                        $selected_location = $loc->id;
                    }
                    $locations[$loc->id] = $loc->name;
                }
            }
        }
        $items = Menu::where('created_by', $admin_id)
            ->where('is_inventory_item', 1)->get();
        $item_list = array();
        $item_list[''] = 'Select Item';
        foreach ($items as $item) {
            $item_list[$item->id] = $item->item;
        }
        $vendors = Vendor::where('created_by', $admin_id)->get();
        $vendor_list = array();
        foreach ($vendors as $vendor) {
            $vendor_list[$vendor->id] = $vendor->name;
        }
        /*Unit array*/
        $units = Unit::lists('name', 'id');
        $units['0'] = 'Select Unit';
        // echo "selected_location <pre>"; print_r($selected_location); echo "</pre>"; 
        // echo "locations <pre>"; print_r($locations); echo "</pre>"; 
        // echo "units <pre>"; print_r($units); echo "</pre>"; 
        // echo "item_list <pre>"; print_r($item_list); echo "</pre>"; 
        // echo "vendor_list <pre>"; print_r($vendor_list); echo "</pre>"; exit;
        return view('purchases.create', array('selected_location' => $selected_location, 'action' => 'add','locations' => $locations, 'units' => $units,'items' => $item_list, 'vendors' => $vendor_list));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $messages = [
            'vendor_id.required' => 'Vendor is required!',
        ];
        $p = Validator::make($request->all(), [
            'vendor_id' => 'required',
        ], $messages);
        if (isset($p) && $p->passes()) {
            $owner_id = Auth::user()->id;
            $save_continue = Input::get('saveContinue');
            $item_ids = Input::get('item_id');
            $unit_ids = Input::get('unit_id');
            $rate = Input::get('rate');
            $quantity = Input::get('quantity');
            $manufacture_date = Input::get('manufacture_date');
            $received_date = Input::get('received_date');
            $invoice_no = Input::get('invoice_no');
            $status = Input::get('status');
            $location_id = Input::get('location_id');
            DB::beginTransaction();
            try {
                $bill = new InvoiceBill();
                $bill->invoice_no = $invoice_no;
                $bill->location_id = $location_id;
                $bill->vendor_id = Input::get('vendor_id');
                $bill->total = Input::get('total_amount');
                $bill->status = $status;
                $bill->invoice_date = Input::get('invoice_date');
                $bill->created_by = $owner_id;
                $bill->updated_by = $owner_id;
                $result = $bill->save();
                if ($result) {
                    for ($i = 0; $i < sizeof($item_ids); $i++) {
                        if ($item_ids[$i] == '' || $item_ids[$i] == null || $quantity[$i] == '' || $quantity[$i] == null || $rate[$i] == '' || $rate[$i] == null) {
                            DB::rollBack();
                            return redirect()->back()->withInput(Input::all())->with('error', 'Please fill all details for items');
                        }
                        else {
                            //get expiry date of item
                            $expiry_date = NULL;
                            if (isset($manufacture_date[$i]) && $manufacture_date[$i] != '') {
                                $item_detail = Menu::find($item_ids[$i]);
                                if (isset($item_detail->expiry) && $item_detail->expiry > 0) {
                                    $expiry_date = date('Y-m-d', strtotime($manufacture_date[$i] . ' + ' . $item_detail->expiry . ' days'));
                                }
                            }
                            $purchase_id = uniqid();
                            $purchase = new Purchase();
                            $purchase->invoice_id = $bill->id;
                            $purchase->purchase_unique_id = $purchase_id;
                            $purchase->item_id = $item_ids[$i];
                            $purchase->unit_id = $unit_ids[$i];
                            $purchase->quantity = $quantity[$i];
                            $purchase->rate = $rate[$i];
                            $purchase->manufacture_date = $manufacture_date[$i];
                            $purchase->received_date = $received_date[$i];
                            $purchase->expiry_date = $expiry_date;
                            $purchase->total = $quantity[$i] * $rate[$i];
                            $result1 = $purchase->save();
                            if ($result1) {
                                //Update Menu Item price which is purchased, Reason : to calculate current total item cost
                                $item = Menu::find($item_ids[$i]);
                                $item->buy_price = $rate[$i];
                                //add stock if configure from outlet setting
                                $add_stock = Location::where('locations.id', $location_id)
                                    ->join('outlets', 'outlets.id', '=', 'locations.outlet_id')
                                    ->select('outlets.add_stock_on_purchase')->get();
                                if (isset($add_stock[0]) && $add_stock[0]->add_stock_on_purchase == 1) {
                                    /*check that item available on this location*/
                                    $check_stock = Stock::where('location_id', $location_id)
                                        ->where('item_id', $item_ids[$i])
                                        ->first();
                                    //if order unit is set than
                                    $qty = $quantity[$i];
                                    $other_units = '';
                                    if (isset($item->secondary_units) && $item->secondary_units != '') {
                                        $units = json_decode($item->secondary_units);
                                        foreach ($units as $key => $u) {
                                            if ($key == $unit_ids[$i]) {
                                                $qty = floatval($qty) * floatval($u);
                                                //update buyprice in menu table
                                                $item->buy_price = $rate[$i] / floatval($u);
                                            }
                                        }
                                    }
                                    /*if stock avalilable than add quantity*/
                                    if (isset($check_stock) && !empty($check_stock)) {
                                        $total_qty = $qty + floatval($check_stock->quantity);
                                        $stock = Stock::find($check_stock->id);
                                        $stock->quantity = $total_qty;
                                        $stock->updated_by = $owner_id;
                                        $stock_result = $stock->save();
                                    }
                                    else {
                                        /*add new entry of stock*/
                                        $stock = new Stock();
                                        $stock->item_id = $item_ids[$i];
                                        $stock->quantity = $qty;
                                        $stock->location_id = $location_id;
                                        $stock->created_by = $owner_id;
                                        $stock->updated_by = $owner_id;
                                        $stock_result = $stock->save();
                                    }
                                    if ($stock_result) {
                                        $stock_history = new StockHistory();
                                        $stock_history->transaction_id = $purchase_id;
                                        $stock_history->to_location = $location_id;
                                        $stock_history->item_id = $item_ids[$i];
                                        $stock_history->type = 'add';
                                        $stock_history->quantity = $qty;
                                        $stock_history->reason = 'Purchase item';
                                        $stock_history->created_by = $owner_id;
                                        $stock_history->updated_by = $owner_id;
                                        $st_history_result = $stock_history->save();
                                        if ($st_history_result) {
                                        /*$stock_age = new StockAge();
                                         $stock_age->location_id = $location_id;
                                         $stock_age->item_id = $item_ids[$i];
                                         $stock_age->quantity = $quantity[$i];
                                         $stock_age->transaction_id = $purchase_id;
                                         $stock_age->expiry_date = $expiry_date;
                                         $stock_age->created_by = $owner_id;
                                         $stock_age->updated_by = $owner_id;
                                         $stock_age->save();*/
                                        }
                                        else {
                                            DB::rollBack();
                                            return redirect()->back()->withInput(Input::all())->with('error', 'Error occurred.');
                                        }
                                    }
                                    else {
                                        DB::rollBack();
                                        return redirect()->back()->withInput(Input::all())->with('error', 'Error occurred.');
                                    }
                                }
                                $item->save();
                            }
                            else {
                                DB::rollBack();
                                return redirect()->back()->withInput(Input::all())->with('error', 'Please fill all details for items');
                            }
                        }
                    }
                }
            }
            catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->withInput(Input::all())->with('error', $e->getMessage());
            }
            if ($result1) {
                DB::commit();
                if (isset($save_continue) && $save_continue == 'true') {
                    return Redirect::route('purchase.create')->with('success', 'New Item Purchased.');
                }
                else {
                    return Redirect::route('purchase.index')->with('success', 'New Item Purchased.');
                }
            }
        }
        else {
            return redirect()->back()->withInput(Input::all())->withErrors($p->errors());
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id = null)
    {
        $id = Input::get('id');
        $bill = InvoiceBill::join('vendors as v', 'v.id', '=', 'invoice_bills.vendor_id')->leftjoin('owners as ow', 'ow.id', '=', 'invoice_bills.created_by')->select('invoice_bills.*', 'ow.user_name as generated_by', 'v.name as vendor', 'v.address as address', 'v.contact_number as contact')->where('invoice_bills.id', $id)->first();
        if (isset($bill) && !empty($bill)) {
            $purchase = Purchase::join('menus as m', 'm.id', '=', 'purchase.item_id')
                ->join('unit as u', 'u.id', '=', 'm.unit_id')
                ->select('purchase.*', 'u.name as unit_name', 'm.item as item_name')
                ->where('invoice_id', $id)
                ->get();
            return view('purchases.billDetail', array('bill' => $bill, 'items' => $purchase));
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        if (isset($id)) {
            $owner_id = Auth::user()->id;
            $admin_id = Owner::menuOwner();
            $outlet_id = Session::get('outlet_session');
            $selected_location = "";
            /*location array*/
            $locations = array('' => 'Select Location');
            $outlet_list = OutletMapper::getOutletIdByOwnerId($owner_id);
            foreach ($outlet_list as $outlet) {
                $locations_list = Location::where('outlet_id', $outlet->outlet_id)->get();
                if (isset($locations_list) && !empty($locations_list)) {
                    foreach ($locations_list as $loc) {
                        if ($outlet->outlet_id == $outlet_id && $loc->default_location == 1) {
                            $selected_location = $loc->id;
                        }
                        $locations[$loc->id] = $loc->name;
                    }
                }
            }
            /*Unit array*/
            // $units = array('0' => 'Select Unit');
            // $unit_list = Unit::lists('name', 'id');
            // $units = array_merge($units, $unit_list);
            $units = ['' => 'Select Unit'] + Unit::lists('name', 'id')->toArray();

            $invoice_bill = InvoiceBill::leftjoin('vendors', 'vendors.id', '=', 'invoice_bills.vendor_id')->leftjoin('locations', 'locations.id', '=', 'invoice_bills.location_id')->select('invoice_bills.*', 'vendors.name as vendor', 'locations.name as location')->where('invoice_bills.id', $id)->first();
            $items = Menu::where('created_by', $admin_id)->where('is_inventory_item', 1)->get();
            $item_list = array();
            $item_list[''] = "Select Item";
            foreach ($items as $item) {
                $item_list[$item->id] = $item->item;
            }
            $vendors = Vendor::where('created_by', $admin_id)->get();
            $vendor_list = array();
            foreach ($vendors as $vendor) {
                $vendor_list[$vendor->id] = $vendor->name;
            }
            // echo "Hello <pre>"; print_r($invoice_bill); echo "</pre>"; exit;
            if (isset($invoice_bill) && $invoice_bill->count() > 0) {
                $purchase_items = Purchase::select('purchase.*', 'menus.item as item', 'menus.id as item_id', 'unit.id as unit_id', 'unit.name as unit_name')->join('menus', 'menus.id', '=', 'purchase.item_id')->join('unit', 'unit.id', '=', 'purchase.unit_id')->where('purchase.invoice_id', $id)->get();
                return view('purchases.edit', array('selected_location' => $invoice_bill->location_id, 'invoice' => $invoice_bill, 'items' => $purchase_items,'locations' => $locations, 'units' => $units, 'action' => 'edit','item_list' => $item_list, 'vendors' => $vendor_list));
            }
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $messages = [
            'vendor_id.required' => 'Vendor is required!',
        ];
        $p = Validator::make(Input::all(), [
            'vendor_id' => 'required',
        ], $messages);
        if (isset($p) && $p->passes()) {
            $owner_id = Auth::user()->id;
            $item_ids = Input::get('item_id');
            $unit_ids = Input::get('unit_id');
            $purchase_id = Input::get('purchase_id');
            $rate = Input::get('rate');
            $quantity = Input::get('quantity');
            $invoice_no = Input::get('invoice_no');
            $status = Input::get('status');
            $manufacture_date = Input::get('manufacture_date');
            $received_date = Input::get('received_date');
            $location_id = Input::get('location_id');
            DB::beginTransaction();
            $bill = InvoiceBill::find($id);
            $bill->invoice_no = $invoice_no;
            $bill->location_id = $location_id;
            $bill->vendor_id = Input::get('vendor_id');
            $bill->total = Input::get('total_amount');
            $bill->status = $status;
            $bill->invoice_date = Input::get('invoice_date');
            $bill->updated_by = $owner_id;
            $result = $bill->save();
            if ($result) {
                for ($i = 0; $i < sizeof($item_ids); $i++) {
                    if ($item_ids[$i] != '' || $item_ids[$i] != null) {
                        if (isset($purchase_id[$i])) {
                            $purchase = Purchase::find($purchase_id[$i]);
                            $purchase_unique_id = $purchase->purchase_unique_id;
                        }
                        else {
                            $purchase_unique_id = uniqid();
                            $purchase = new Purchase();
                            $purchase->purchase_unique_id = $purchase_unique_id;
                        }
                        //get expiry date of item
                        $expiry_date = NULL;
                        if (isset($manufacture_date[$i]) && $manufacture_date[$i] != '') {
                            $item_detail = Menu::find($item_ids[$i]);
                            if (isset($item_detail->expiry) && $item_detail->expiry > 0) {
                                $expiry_date = date('Y-m-d', strtotime($manufacture_date[$i] . ' + ' . $item_detail->expiry . ' days'));
                            }
                        }
                        $purchase->invoice_id = $id;
                        $purchase->item_id = $item_ids[$i];
                        $purchase->unit_id = $unit_ids[$i];
                        $purchase->quantity = $quantity[$i];
                        $purchase->rate = $rate[$i];
                        $purchase->manufacture_date = $manufacture_date[$i];
                        $purchase->received_date = $received_date[$i];
                        $purchase->expiry_date = $expiry_date;
                        $purchase->total = $quantity[$i] * $rate[$i];
                        $result1 = $purchase->save();
                        if ($result1) {
                            //Update Menu Item price which is purchased, Reason : to calculate current total item cost
                            $item = Menu::find($item_ids[$i]);
                            if (isset($item) && !is_null($item)) {
                                $item->buy_price = $rate[$i];
                                $item->save();
                            }
                            //add stock if configure from outlet setting
                            $add_stock = Location::where('locations.id', $location_id)
                                ->join('outlets', 'outlets.id', '=', 'locations.outlet_id')
                                ->select('outlets.add_stock_on_purchase')->first();
                            if (isset($add_stock) && $add_stock->add_stock_on_purchase == 1) {
                                /*check that item available on this location in history table*/
                                $check_stock = StockHistory::where('transaction_id', $purchase_unique_id)
                                    ->where('item_id', $item_ids[$i])
                                    ->where('to_location', $location_id)
                                    ->first();
                                //if order unit is set than
                                $qty = $quantity[$i];
                                if (isset($item->secondary_units) && $item->secondary_units != '') {
                                    $units = json_decode($item->secondary_units);
                                    foreach ($units as $key => $u) {
                                        if ($key == $unit_ids[$i]) {
                                            $qty = floatval($qty) * floatval($u);
                                        }
                                    }
                                }
                                /*if stock avalilable than add quantity*/
                                if (isset($check_stock) && !is_null($check_stock)) {
                                    //check stock in stock table
                                    $check_stock1 = Stock::where('location_id', $location_id)
                                        ->where('item_id', $item_ids[$i])
                                        ->first();

                                    if (isset($check_stock1) && !empty($check_stock1)) {
                                        //decrease previous quantity and add new quantity
                                        $loc_qty = $check_stock1->quantity;
                                        $loc_qty = $loc_qty - $check_stock->quantity;
                                        $loc_qty = $loc_qty + $qty;
                                        $check_stock1->quantity = $loc_qty;
                                        $status_stock = $check_stock1->save();
                                        if ($status_stock) {
                                            //update history table with new quantity
                                            $check_stock->quantity = $qty;
                                            $check_stock->updated_by = $owner_id;
                                            $check_stock->save();
                                        }
                                    }
                                }
                                else {
                                    /*check that item available on this location*/
                                    $check_stock = Stock::where('location_id', $location_id)
                                        ->where('item_id', $item_ids[$i])
                                        ->first();
                                    /*if stock avalilable than add quantity*/

                                    if (isset($check_stock) && !empty($check_stock)) {
                                        $total_qty = $qty + floatval($check_stock->quantity);
                                        $stock = Stock::find($check_stock->id);
                                        $stock->quantity = $total_qty;
                                        $stock->updated_by = $owner_id;
                                        $stock_result = $stock->save();
                                    }
                                    else {
                                        /*add new entry of stock*/
                                        $stock = new Stock();
                                        $stock->item_id = $item_ids[$i];
                                        $stock->quantity = $qty;
                                        $stock->location_id = $location_id;
                                        $stock->created_by = $owner_id;
                                        $stock->updated_by = $owner_id;
                                        $stock_result = $stock->save();
                                    }
                                    if ($stock_result) {
                                        $stock_history = new StockHistory();
                                        $stock_history->transaction_id = $purchase_unique_id;
                                        $stock_history->to_location = $location_id;
                                        $stock_history->item_id = $item_ids[$i];
                                        $stock_history->type = 'add';
                                        $stock_history->quantity = $qty;
                                        $stock_history->reason = 'Purchase item';
                                        $stock_history->created_by = $owner_id;
                                        $stock_history->updated_by = $owner_id;
                                        $st_history_result = $stock_history->save();
                                        if (!$st_history_result) {
                                            DB::rollBack();
                                            return redirect()->back()->withInput(Input::all())->with('error', 'Error occurred.');
                                        }
                                    }
                                }
                            }
                        }
                    }
                    else {
                        DB::rollBack();
                        return redirect()->back()->withInput(Input::all())->with('error', 'Please fill all item details');
                    }
                }
            }
            if ($result) {
                DB::commit();
                return Redirect::route('purchase.index')->with('success', 'Purchase information updated successfully!');
            }
        }
        else {
            return redirect()->back()->withInput(Input::all())->withErrors($p->errors());
        }
    }
    //remove item from purchase if available
    public function removePurchaseItem()
    {
        $owner_id = Auth::id();
        $id = Input::get('id');
        $loc_id = Input::get('location_id');
        if (isset($id) && $id != '') {
            DB::beginTransaction();
            $purchase = Purchase::find($id);
            if (isset($purchase) && sizeof($purchase) > 0) {
                //add stock if configure from outlet setting
                $add_stock = Location::where('locations.id', $loc_id)
                    ->join('outlets', 'outlets.id', '=', 'locations.outlet_id')
                    ->select('outlets.add_stock_on_purchase')->first();
                if (isset($add_stock) && $add_stock->add_stock_on_purchase == 1) {
                    /*check that item available on this location in history table*/
                    $check_stock = StockHistory::where('transaction_id', $purchase->purchase_unique_id)
                        ->where('item_id', $purchase->item_id)
                        ->where('to_location', $loc_id)
                        ->first();
                    /*if stock avalilable than add quantity*/
                    if (isset($check_stock) && sizeof($check_stock) > 0) {
                        //check stock in stock table
                        $check_stock1 = Stock::where('location_id', $loc_id)
                            ->where('item_id', $purchase->item_id)
                            ->first();
                        if (isset($check_stock1) && sizeof($check_stock1) > 0) {
                            //decrease quantity
                            $loc_qty = $check_stock1->quantity;
                            $loc_qty = $loc_qty - $check_stock->quantity;
                            $check_stock1->quantity = $loc_qty;
                            $status_stock = $check_stock1->save();
                            if ($status_stock) {
                                //update history table with new quantity
                                $check_stock->reason = 'Stock remove on purchase item removed';
                                $check_stock->quantity = 0;
                                $check_stock->updated_by = $owner_id;
                                $check_stock->save();
                            }
                            else {
                                DB::rollback();
                                return 'error';
                            }
                        }
                    }
                }
                //update invoice total on delete item
                $bill = InvoiceBill::find($purchase->invoice_id);
                if (isset($bill) && sizeof($bill) > 0) {
                    $total = $bill->total - $purchase->total;
                    $bill->update(['total' => $total]);
                }
                //soft delete record
                $purchase->delete();
                DB::commit();
                return 'success';
            }
            else {
                return 'success';
            }
        }
        else {
            return 'error';
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $owner_id = Auth::id();
        $bill = InvoiceBill::find($id);
        if (isset($bill) && sizeof($bill) > 0) {
            $purchase = Purchase::where('invoice_id', $id)->get();
            if (isset($purchase) && sizeof($purchase) > 0) {
                foreach ($purchase as $pur) {
                    /*check that item available on this location in history table*/
                    $check_stock = StockHistory::where('transaction_id', $pur->purchase_unique_id)
                        ->where('item_id', $pur->item_id)
                        ->where('to_location', $bill->location_id)
                        ->first();
                    /*if stock avalilable than remove quantity*/
                    if (isset($check_stock) && sizeof($check_stock) > 0) {
                        //check stock in stock table
                        $check_stock1 = Stock::where('location_id', $bill->location_id)
                            ->where('item_id', $pur->item_id)
                            ->first();
                        if (isset($check_stock1) && sizeof($check_stock1) > 0) {
                            //decrease previous quantity
                            $loc_qty = $check_stock1->quantity;
                            $loc_qty = $loc_qty - $check_stock->quantity;
                            $check_stock1->quantity = $loc_qty;
                            $status_stock = $check_stock1->save();
                            if ($status_stock) {
                                //update history table with new quantity
                                $check_stock->reason = 'Stock remove on invoice removed';
                                $check_stock->quantity = 0;
                                $check_stock->updated_by = $owner_id;
                                $check_stock->save();
                            }
                        }
                    }
                    //remove purchase item
                    $pur->delete();
                }
            }
            $bill->delete();
            $invalid_purchase = InvalidPurchaseImport::where('invoice_id', $id)->get();
            if (isset($invalid_purchase) && sizeof($invalid_purchase) > 0) {
                InvalidPurchaseImport::where('invoice_id', $id)->delete();
            }
            Session::flash('success', 'Invoice bill has been deleted successfully!');
        }
        else {
            Session::flash('error', 'Invoice bill not found!');
        }
        return Redirect::to('purchase');
    }
    public function processinvoice(Request $request)
    {
        $owner_id = Auth::id();
        if ($request->ajax()) {
            $vendor_id = Input::get('vendor');
            $from_date = Input::get('from_date');
            $to_date = Input::get('to_date');
            $flag = Input::get('flag');
            if ($flag == 'invoiced') {
                $result = Purchase::where('status', '!=', 'invoice')
                    ->join('menus', 'menus.id', '=', 'purchases.item_id')
                    ->join('vendors', 'vendors.id', '=', 'purchases.vendor_id')
                    ->where('purchases.created_by', $owner_id)
                    ->where('purchases.vendor_id', $vendor_id)
                    ->whereBetween('purchases.purchase_date', array($from_date, $to_date))
                    ->select('purchases.status', 'purchases.created_by', 'purchases.id', 'vendors.name', 'purchases.purchase_date', 'menus.item', 'purchases.quantity', 'purchases.rate')
                    ->get();
                return view('purchases.processinvoiceList', array('purchases' => $result, 'flag' => $flag));
            }
            else {
                $result = Purchase::where('status', '!=', 'paid')
                    ->join('menus', 'menus.id', '=', 'purchases.item_id')
                    ->join('vendors', 'vendors.id', '=', 'purchases.vendor_id')
                    ->where('purchases.created_by', $owner_id)
                    ->where('purchases.vendor_id', $vendor_id)
                    ->whereBetween('purchases.purchase_date', array($from_date, $to_date))
                    ->select('purchases.status', 'purchases.created_by', 'purchases.id', 'vendors.name', 'purchases.purchase_date', 'menus.item', 'purchases.quantity', 'purchases.rate')
                    ->get();
                return view('purchases.processinvoiceList', array('purchases' => $result, 'flag' => $flag));
            }
        }
        $vendors = array('0' => 'Select Vendors');
        $vendor_list = Vendor::where('created_by', $owner_id)->lists('name', 'id');
        $vendors = array_merge($vendors, $vendor_list);
        return view("purchases.processinvoice", array('vendors' => $vendors));
    }
    public function invoiceupdate()
    {
        $ids = Input::get('ids');
        $updated = 0;
        if (isset($ids) && sizeof($ids) > 0) {
            foreach ($ids as $id) {
                $pay_1 = InvoiceBill::find($id);
                if ($pay_1->status != 'paid') {
                    $pay_1->status = 'paid';
                    $pay_1->save();
                    $updated++;
                }
            }
        }
        $status = 'success';
        return json_encode(array('status' => $status, 'updated' => $updated));
    }
    public function getPurchaseStockDetail()
    {
        $ids = Input::get('ids');
        $flag = Input::get('flag');
        $owner_id = Auth::id();
        if (isset($ids) && !empty($ids)) {
            $stock_arr = array();
            foreach ($ids as $id) {
                $invoice = InvoiceBill::find($id);
                $purchase = Purchase::join('menus as m', 'm.id', '=', 'purchase.item_id')
                    ->join('unit as u', 'u.id', '=', 'm.unit_id')
                    ->select('purchase.*', 'm.item as item_name', 'u.name as unit_name', 'm.secondary_units as secondary_units')
                    ->where('invoice_id', $id)
                    ->get();
       
                if (isset($purchase) && $purchase->isEmpty()) {
                    DB::beginTransaction();
                    foreach ($purchase as $pur) {
                        //if order unit is set than
                        $qty = $pur->quantity;
                        $other_units = '';
                        if (isset($pur->secondary_units) && $pur->secondary_units != '') {
                            $units = json_decode($pur->secondary_units);
                            foreach ($units as $key => $u) {
                                if ($key == $pur->unit_id) {
                                    $qty = floatval($qty) * floatval($u);
                                }
                            }
                        }
                        if ($flag == 'add') {
                            /*check that item available on this location in history table*/
                            $check_stock = StockHistory::where('transaction_id', $pur->purchase_unique_id)
                                ->where('item_id', $pur->item_id)
                                ->where('to_location', $invoice->location_id)
                                ->first();

                            /*if purchase stock not added but its 0 quantity*/
                            if (isset($check_stock) && !empty($check_stock) && $check_stock->quantity == 0) {
                                $check_stock->quantity = $qty;
                                $check_stock->reason = 'Purchase item';
                                $check_stock->updated_by = $owner_id;
                                $check_stock->save();
                            }
                            else if (!isset($check_stock)) {
                                $stock_history = new StockHistory();
                                $stock_history->transaction_id = $pur->purchase_unique_id;
                                $stock_history->to_location = $invoice->location_id;
                                $stock_history->item_id = $pur->item_id;
                                $stock_history->type = 'add';
                                $stock_history->quantity = $qty;
                                $stock_history->reason = 'Purchase item';
                                $stock_history->created_by = $owner_id;
                                $stock_history->updated_by = $owner_id;
                                $st_history_result = $stock_history->save();
                                if (!$st_history_result) {
                                    DB::rollBack();
                                    return 'error';
                                }
                            }
                            else {
                                continue;
                            }
                            //check stock in stock table
                            $check_stock1 = Stock::where('location_id', $invoice->location_id)
                                ->where('item_id', $pur->item_id)
                                ->first();

                            if (isset($check_stock1) && !empty($check_stock1) ) {
                                //and add new quantity
                                $loc_qty = $check_stock1->quantity;
                                $loc_qty = $loc_qty + $qty;
                                $check_stock1->quantity = $loc_qty;
                                $status_stock = $check_stock1->save();
                            }
                            else {
                                //add new entry
                                $stock = new Stock();
                                $stock->item_id = $pur->item_id;
                                $stock->quantity = $qty;
                                $stock->location_id = $invoice->location_id;
                                $stock->created_by = $owner_id;
                                $stock->updated_by = $owner_id;
                                $status_stock = $stock->save();
                                if (!$status_stock) {
                                    DB::rollBack();
                                    return 'error';
                                }
                            }
                        }
                        else if ($flag == 'remove') {
                            /*check that item available on this location in history table*/
                            $check_stock = StockHistory::where('transaction_id', $pur->purchase_unique_id)
                                ->where('item_id', $pur->item_id)
                                ->where('to_location', $invoice->location_id)
                                ->first();
                            /*if stock avalilable than remove quantity*/
                            if (isset($check_stock) && !empty($check_stock)) {
                                //check stock in stock table
                                $check_stock1 = Stock::where('location_id', $invoice->location_id)
                                    ->where('item_id', $pur->item_id)
                                    ->first();
                                if (isset($check_stock1) && !empty($check_stock1)) {
                                    //decrease previous quantity
                                    $loc_qty = $check_stock1->quantity;
                                    $loc_qty = $loc_qty - $check_stock->quantity;
                                    $check_stock1->quantity = $loc_qty;
                                    $status_stock = $check_stock1->save();
                                }
                                else {
                                    //add new entry
                                    $stock = new Stock();
                                    $stock->item_id = $pur->item_id;
                                    $stock->quantity = 0 - $qty;
                                    $stock->location_id = $invoice->location_id;
                                    $stock->created_by = $owner_id;
                                    $stock->updated_by = $owner_id;
                                    $status_stock = $stock->save();
                                }
                                if ($status_stock) {
                                    //update history table with new quantity
                                    $check_stock->reason = 'Stock remove on invoice revoke manually';
                                    $check_stock->quantity = 0;
                                    $check_stock->type = 'remove';
                                    $check_stock->updated_by = $owner_id;
                                    $status1 = $check_stock->save();
                                }
                                else {
                                    DB::rollBack();
                                    return 'error';
                                }
                            }
                        }
                        else {
                            $check_stock = Stock::where('location_id', $invoice->location_id)->where('item_id', $pur->item_id)->first();
                            if (array_key_exists($pur->item_id, $stock_arr)) {
                                $stock_arr[$pur->item_id]['decrease_stock'] = $stock_arr[$pur->item_id]['decrease_stock'] + $qty;
                            }
                            else {
                                $stock_arr[$pur->item_id]['name'] = $pur->item_name;
                                if (isset($check_stock) && !empty($check_stock)) {
                                    $stock_arr[$pur->item_id]['stock'] = $check_stock->quantity;
                                    $stock_arr[$pur->item_id]['unit'] = $pur->unit_name;
                                }
                                else {
                                    $stock_arr[$pur->item_id]['stock'] = 0;
                                    $stock_arr[$pur->item_id]['unit'] = $pur->unit_name;
                                    ;
                                }
                                $stock_arr[$pur->item_id]['decrease_stock'] = $qty;
                            }
                        }
                    }
                }
            }
            if ($flag == 'show') {
                return view('purchases.purchaseItemStockDetail', array('stock_arr' => $stock_arr));
            }
            else {
                DB::commit();
                return 'success';
            }
        }
    }
    public function import()
    {
        $owner_id = Auth::id();
        $response = array();
        $vendor_id = Input::get('vendor_id');
        $invoice_date = Input::get('invoice_date');
        $invoice_no = Input::get('invoice_no');
        $status = Input::get('status');
        $location_id = Input::get('location_id');
        $invalid_count = 0;
        if (Input::hasFile('file')) {
            $bill = new InvoiceBill();
            $bill->invoice_no = $invoice_no;
            $bill->location_id = $location_id;
            $bill->vendor_id = $vendor_id;
            $bill->total = 0;
            $bill->status = $status;
            $bill->invoice_date = $invoice_date;
            $bill->created_by = $owner_id;
            $bill->updated_by = $owner_id;
            $file = Input::file('file');
            //check type of file
            $type = ($file->getMimeType());
            if ($type == 'application/vnd.ms-office' || $type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
                $path = $file->getRealPath();
                $data = Excel::load($path, function ($reader) {
                })->get();
                if (isset($data) && sizeof($data) > 0) {
                    $result = $bill->save();
                    //$result = 1;
                    if ($result) {
                        $total = 0;
                        foreach ($data as $itm) {
                            $check_response = $this::addItemToInvoiceBill($itm, $bill->id, $location_id);
                            $total += $check_response['total'];
                            if ($check_response['invalid'] == 1) {
                                $invalid_count++;
                            }
                        }
                        //update total for uploaded invoice
                        $inv_bill = InvoiceBill::find($bill->id);
                        $inv_bill->total += $total;
                        $inv_bill->save();
                        $response['status'] = 'success';
                        $response['message'] = 'Purchase invoice has been uploaded successfully.';
                    }
                    else {
                        $response['status'] = 'error';
                        $response['message'] = 'There is some error occurred, please try again later.';
                    }
                }
                else {
                    $response['status'] = 'error';
                    $response['message'] = 'No data available for upload, please check file.';
                }
            }
            else {
                $response['status'] = 'error';
                $response['message'] = 'Invalid file extension, Please upload only .xls file.';
            }
        }
        else {
            $response['status'] = 'error';
            $response['message'] = 'Please upload file.';
        }
        if ($invalid_count > 0) {
            $response['message'] = "There are " . $invalid_count . " items are invalid, Please check invalid puchase import module for more details";
        }
        return Response::json(array(
            'status' => $response['status'],
            'message' => $response['message'],
            'invalid_count' => $invalid_count,
        ), 200);
    }
    public function addItemToInvoiceBill($item, $invoice_id, $location_id)
    {
        $menu_owner = Owner::menuOwner();
        $owner_id = Auth::id();
        if (trim($item->item_code) != "" || trim($item->unit) != "" || trim($item->qty) != "" || trim($item->rate) != "") {
            $check_item = Menu::where('item_code', $item->item_code)->where('created_by', $menu_owner)->first();
            $unit = Unit::where('name', 'like', $item->unit)->first();
            $response = array();
            $response['total'] = 0;
            if (isset($check_item) && sizeof($check_item) > 0 && isset($unit) && sizeof($unit) > 0) {
                $purchase_id = uniqid();
                $purchase = new Purchase();
                $purchase->invoice_id = $invoice_id;
                $purchase->purchase_unique_id = $purchase_id;
                $purchase->item_id = $check_item->id;
                $purchase->unit_id = $unit->id;
                $purchase->quantity = $item->qty;
                $purchase->rate = $item->rate;
                $purchase->total = $item->qty * $item->rate;
                $result1 = $purchase->save();
                $check_item->buy_price = $item->rate;
                $check_item->save();
                if ($result1) {
                    $response['total'] += $item->qty * $item->rate;
                    //add stock if configure from outlet setting
                    $add_stock = Location::where('locations.id', $location_id)
                        ->join('outlets', 'outlets.id', '=', 'locations.outlet_id')
                        ->select('outlets.add_stock_on_purchase')->get();
                    if (isset($add_stock[0]) && $add_stock[0]->add_stock_on_purchase == 1) {
                        /*check that item available on this location*/
                        $check_stock = Stock::where('location_id', $location_id)
                            ->where('item_id', $check_item->id)
                            ->first();
                        //if order unit is set than
                        $qty = $item->qty;
                        $other_units = '';
                        $check_item->buy_price = $item->rate;
                        if (isset($check_item->secondary_units) && $check_item->secondary_units != '') {
                            $units = json_decode($check_item->secondary_units);
                            foreach ($units as $key => $u) {
                                if ($key == $unit->id) {
                                    $qty = floatval($qty) * floatval($u);
                                    //update buyprice in menu table
                                    $check_item->buy_price = $item->rate / floatval($u);
                                }
                            }
                        }
                        $check_item->save();
                        /*if stock avalilable than add quantity*/
                        if (isset($check_stock) && sizeof($check_stock) > 0) {
                            $total_qty = $qty + floatval($check_stock->quantity);
                            $stock = Stock::find($check_stock->id);
                            $stock->quantity = $total_qty;
                            $stock->updated_by = $owner_id;
                            $stock_result = $stock->save();
                        }
                        else {
                            /*add new entry of stock*/
                            $stock = new Stock();
                            $stock->item_id = $check_item->id;
                            $stock->quantity = $qty;
                            $stock->location_id = $location_id;
                            $stock->created_by = $owner_id;
                            $stock->updated_by = $owner_id;
                            $stock_result = $stock->save();
                        }
                        if ($stock_result) {
                            $stock_history = new StockHistory();
                            $stock_history->transaction_id = $purchase_id;
                            $stock_history->to_location = $location_id;
                            $stock_history->item_id = $check_item->id;
                            $stock_history->type = 'add';
                            $stock_history->quantity = $qty;
                            $stock_history->reason = 'Purchase item';
                            $stock_history->created_by = $owner_id;
                            $stock_history->updated_by = $owner_id;
                            $st_history_result = $stock_history->save();
                            if ($st_history_result) {
                            /*$stock_age = new StockAge();
                             $stock_age->location_id = $location_id;
                             $stock_age->item_id = $item_ids[$i];
                             $stock_age->quantity = $quantity[$i];
                             $stock_age->transaction_id = $purchase_id;
                             $stock_age->expiry_date = $expiry_date;
                             $stock_age->created_by = $owner_id;
                             $stock_age->updated_by = $owner_id;
                             $stock_age->save();*/
                            }
                        }
                    }
                }
                $response['invalid'] = 0;
            }
            else {
                $item_id = 0;
                $unit_id = 0;
                $msg = "";
                if (isset($check_item) && sizeof($check_item) > 0) {
                    $item_id = $check_item->id;
                }
                else {
                    $msg = "Item code not found please add item before insert";
                }
                if (isset($unit) && sizeof($unit) > 0) {
                    $unit_id = $unit->id;
                }
                else {
                    if (isset($msg) && $msg != "") {
                        $msg .= "<br>Unit not found please contact admin to add unit";
                    }
                    else {
                        $msg = "Unit not found please contact admin to add unit";
                    }
                }
                $invalid = new InvalidPurchaseImport();
                $invalid->invoice_id = $invoice_id;
                $invalid->item_code = $item->item_code;
                $invalid->item_id = $item_id;
                $invalid->unit_id = $unit_id;
                $invalid->quantity = $item->qty;
                $invalid->rate = $item->rate;
                $invalid->reason = $msg;
                $invalid->created_by = $owner_id;
                $invalid->updated_by = $owner_id;
                $invalid->save();
                $response['invalid'] = 1;
            }
        }
        else {
            $item_id = 0;
            $unit_id = 0;
            $msg = "";
            if (isset($check_item) && sizeof($check_item) > 0) {
                $item_id = $check_item->id;
            }
            else {
                $msg = "Item code not found please add item before insert";
            }
            if (isset($check_item) && sizeof($check_item) > 0) {
                $item_id = $check_item->id;
            }
            else {
                $msg = "Item code not found please add item before insert";
            }
            if (isset($item->rate) && sizeof($item->rate) > 0) {
                $item_id = $check_item->id;
            }
            else {
                $msg = "Item buy price could not be empty";
            }
            if (isset($item->qty) && sizeof($item->qty) > 0) {
                $item_id = $check_item->id;
            }
            else {
                $msg = "Item qty price could not be empty";
            }
            if (isset($unit) && sizeof($unit) > 0) {
                $unit_id = $unit->id;
            }
            else {
                if (isset($msg) && $msg != "") {
                    $msg .= "<br>Unit not found please contact admin to add unit";
                }
                else {
                    $msg = "Unit not found please contact admin to add unit";
                }
            }
            $invalid = new InvalidPurchaseImport();
            $invalid->invoice_id = $invoice_id;
            $invalid->item_code = $item->item_code;
            $invalid->item_id = $item_id;
            $invalid->unit_id = $unit_id;
            $invalid->quantity = $item->qty;
            $invalid->rate = $item->rate;
            $invalid->reason = $msg;
            $invalid->created_by = $owner_id;
            $invalid->updated_by = $owner_id;
            $invalid->save();
            $response['invalid'] = 1;
        }
        return $response;
    }
    public function samplePurchase()
    {
        $path = public_path('item_purchase.xls');
        return response()->download($path);
    }
    public function invalidImportItems(Request $request)
    {
        $owner_id = Auth::id();
        $invalids = InvalidPurchaseImport::where('created_by', $owner_id)->get();
        if ($request->ajax()) {
            $input = Input::all();
            $response = array();
            $search = $input['sSearch'];
            $sort = $input['sSortDir_0'];
            $sortCol = $input['iSortCol_0'];
            $sortColName = $input['mDataProp_' . $sortCol];
            if ($sortColName == "invoice_no") {
                $sort_field = 'invoice_bills.invoice_no';
            }
            elseif ($sortColName == "vendor") {
                $sort_field = 'vendors.name';
            }
            elseif ($sortColName == "item") {
                $sort_field = 'menus.item';
            }
            elseif ($sortColName == "qty") {
                $sort_field = 'invalid_purchase_import.quantity';
            }
            elseif ($sortColName == "reason") {
                $sort_field = 'invalid_purchase_import.reason';
            }
            elseif ($sortColName == "invoice_date") {
                $sort_field = 'invoice_bills.invoice_date';
            }
            else {
                $sort_field = 'invoice_bills.invoice_date';
                $sort = 'DESC';
            }
            $total_colomns = $input['iColumns'];
            $search_col = '';
            $query_filter = '';
            for ($j = 0; $j <= $total_colomns - 1; $j++) {
                //if ($j == 0) continue;
                if (isset($input['sSearch_' . $j]) && $input['sSearch_' . $j] != '') {
                    $search = $input['sSearch_' . $j];
                    $searchColName = $input['mDataProp_' . $j];
                    if ($searchColName == 'invoice_no') {
                        if (isset($search_col) && $search_col != '') {
                            $search_col .= " AND invoice_bills.invoice_no like '%$search%'";
                        }
                        else {
                            $search_col = "invoice_bills.invoice_no like '%$search%'";
                        }
                    }
                    else if ($searchColName == 'vendor') {
                        if (isset($search_col) && $search_col != '') {
                            $search_col .= " AND vendors.name like '%$search%'";
                        }
                        else {
                            $search_col = "vendors.name like '%$search%'";
                        }
                    }
                    else if ($searchColName == 'reason') {
                        if (isset($search_col) && $search_col != '') {
                            $search_col .= " AND invalid_purchase_import.reason like '%$search%'";
                        }
                        else {
                            $search_col = "invalid_purchase_import.reason like '%$search%'";
                        }
                    }
                    else if ($searchColName == 'item') {
                        if (isset($search_col) && $search_col != '') {
                            $search_col .= " AND menus.item = '$search'";
                        }
                        else {
                            $search_col = "menus.item = '$search'";
                        }
                    }
                    else if ($searchColName == 'qty') {
                        if (isset($search_col) && $search_col != '') {
                            $search_col .= " AND invalid_purchase_import.quantity = '$search'";
                        }
                        else {
                            $search_col = "invalid_purchase_import.quantity = '$search'";
                        }
                    }
                    else if ($searchColName == 'rate') {
                        if (isset($search_col) && $search_col != '') {
                            $search_col .= " AND invalid_purchase_import.rate = '$search'";
                        }
                        else {
                            $search_col = "invalid_purchase_import.rate = '$search'";
                        }
                    }
                    else if ($searchColName == 'location') {
                        if (isset($search_col) && $search_col != '') {
                            $search_col .= " AND locations.id = '$search'";
                        }
                        else {
                            $search_col = "locations.id ='$search'";
                        }
                    }
                    else if ($searchColName == 'invoice_date') {
                        //echo 'here';exit;
                        $from = $search . " 00:00:00";
                        $to = $search . " 23:59:59";
                        if (isset($search_col) && $search_col != '') {
                            $search_col .= " AND invoice_bills.invoice_date like '$search'";
                        }
                        else {
                            $search_col = "invoice_bills.invoice_date like '$search'";
                        }
                    }
                }
            }
            //print_r($search_col);exit;
            if ($search_col == '')
                $search_col = '1=1';
            $where = 'invalid_purchase_import.created_by =' . $owner_id . ' AND ';
            $total_records = InvalidPurchaseImport::leftjoin('invoice_bills', 'invoice_bills.id', '=', 'invalid_purchase_import.invoice_id')
                ->leftjoin('vendors', 'vendors.id', '=', 'invoice_bills.vendor_id')
                ->leftjoin('locations', 'locations.id', '=', 'invoice_bills.location_id')
                ->leftjoin('menus', 'invalid_purchase_import.item_id', '=', 'menus.id')
                ->whereRaw(" $where ($search_col)")
                ->count();
            $invoice_result = InvalidPurchaseImport::leftjoin('invoice_bills', 'invoice_bills.id', '=', 'invalid_purchase_import.invoice_id')
                ->leftjoin('vendors', 'vendors.id', '=', 'invoice_bills.vendor_id')
                ->leftjoin('locations', 'locations.id', '=', 'invoice_bills.location_id')
                ->leftjoin('menus', 'invalid_purchase_import.item_id', '=', 'menus.id')
                ->select('invalid_purchase_import.*', 'invoice_bills.invoice_no', 'invoice_bills.invoice_date', 'vendors.name as vendor', 'locations.name as location')
                ->whereRaw(" $where ($search_col)")
                ->take($input['iDisplayLength'])
                ->skip($input['iDisplayStart'])
                ->orderBy($sort_field, $sort)
                ->orderBy('invalid_purchase_import.id', 'desc')->get();
            //$total_records = sizeof($invoice_result);
            if ($total_records > 0) {
                $i = 0;
                foreach ($invoice_result as $inv) {
                    $response['result'][$i]['DT_RowId'] = $inv->id;
                    $response['result'][$i]['check_col'] = "";
                    $response['result'][$i]['invoice_no'] = $inv->invoice_no;
                    $response['result'][$i]['vendor'] = $inv->vendor;
                    $response['result'][$i]['item'] = $inv->item == "" ? '-' : $inv->item;
                    $response['result'][$i]['qty'] = $inv->quantity;
                    $response['result'][$i]['rate'] = $inv->rate;
                    $response['result'][$i]['reason'] = $inv->reason;
                    $response['result'][$i]['invoice_date'] = $inv->invoice_date;
                    $response['result'][$i]['status'] = ucfirst($inv->status);
                    $response['result'][$i]['action'] = '<a href="/invalidItem/' . $inv->id . '/edit" title="Edit"><span class="zmdi zmdi-edit" ></span></a>' .
                        '&nbsp;&nbsp;&nbsp;&nbsp;<a onclick="warn(this,' . $inv->id . ')"  href="#" title="Delete"><span class="zmdi zmdi-close"></span></a>';
                    $i++;
                }
            }
            else {
                $total_records = 0;
                $response['result'] = array();
            }
            $response['iTotalRecords'] = $total_records;
            $response['iTotalDisplayRecords'] = $total_records;
            $response['aaData'] = $response['result'];
            $locations = Location::where('created_by', $owner_id)->get();
            $response['locations'] = $locations;
            //print_r($response);exit;
            return json_encode($response);
        }
        return view('purchases.invalidImportItems');
    }
    public function invalidItemImportEdit($id)
    {
        $edit_id = $id;
        $outlet_id = Session::get('outlet_session');
        $admin_id = Owner::menuOwner();
        $items = Menu::where('created_by', $admin_id)
            ->where('is_inventory_item', 1)->get();
        $item_list = array();
        $item_list[''] = "Select Item";
        foreach ($items as $item) {
            $item_list[$item->id] = $item->item;
        }
        $vendors = Vendor::where('created_by', $admin_id)->get();
        $vendor_list = array();
        foreach ($vendors as $vendor) {
            $vendor_list[$vendor->id] = $vendor->name;
        }
        /*Unit array*/
        $units = array('0' => 'Select Unit');
        $unit_list = Unit::lists('name', 'id');
        $units = array_merge($units, $unit_list);
        
        $invoice_bill = InvalidPurchaseImport::leftjoin('invoice_bills', 'invoice_bills.id', '=', 'invalid_purchase_import.invoice_id')
            ->leftjoin('vendors', 'vendors.id', '=', 'invoice_bills.vendor_id')
            ->leftjoin('locations', 'locations.id', '=', 'invoice_bills.location_id')
            ->select('invalid_purchase_import.*', 'invoice_bills.vendor_id', 'invoice_bills.location_id', 'invoice_bills.status', 'invoice_bills.invoice_no', 'vendors.name as vendor', 'locations.name as location')
            ->where('invalid_purchase_import.id', $edit_id)
            ->first();
        $locations = array('' => 'Select Location');
        $outlet_list = OutletMapper::getOutletIdByOwnerId($admin_id);
        foreach ($outlet_list as $outlet) {
            $locations_list = Location::where('outlet_id', $outlet->outlet_id)->get();
            if (isset($locations_list) && sizeof($locations_list) > 0) {
                foreach ($locations_list as $loc) {
                    if ($outlet->outlet_id == $outlet_id && $loc->default_location == 1) {
                        $selected_location = $loc->id;
                    }
                    $locations[$loc->id] = $loc->name;
                }
            }
        }
        return view('purchases.invalidImportItemForm', array('invoice' => $invoice_bill,
            'locations' => $locations, 'units' => $units,
            'item_list' => $item_list, 'vendors' => $vendor_list));
    }
    public function invalidImportSubmit($id)
    {
        $owner_id = Auth::user()->id;
        $item_id = Input::get('item_id');
        $unit_id = Input::get('unit_id');
        $rate = Input::get('rate');
        $quantity = Input::get('quantity');
        $location_id = Input::get('location_id');
        $item = array();
        DB::beginTransaction();
        $invPurc = InvalidPurchaseImport::find($id);
        $invPurc->updated_by = $owner_id;
        $invPurc->deleted_by = $owner_id;
        $result = $invPurc->save();
        $invoice_id = $invPurc->invoice_id;
        $invPurc->delete();
        //Get Item from item_id
        $item_arr = Menu::find($item_id);
        //Create an array of item for insertion
        $item['item_code'] = $item_arr['item_code'];
        $item['unit'] = Unit::find($unit_id)->name;
        $item['qty'] = $quantity;
        $item['rate'] = $rate;
        $this::addItemToInvoiceBill((object)$item, $invoice_id, $location_id);
        $invoice = InvoiceBill::find($invoice_id);
        $invoice->total += $rate * $quantity;
        $invoice->updated_by = Auth::user()->id;
        $invoice->save();
        if ($result) {
            DB::commit();
            return Redirect::route('purchase.index')->with('success', 'Purchase information updated successfully!');
        }
    }
    public function destroyInvalid($id)
    {
        $owner_id = Auth::id();
        $invalid_purchase = InvalidPurchaseImport::find($id);
        if (isset($invalid_purchase) && sizeof($invalid_purchase) > 0) {
            $invoice_id = $invalid_purchase->invoice_id;
            $purchase = InvoiceBill::find($invoice_id);
            if (isset($purchase) && sizeof($purchase) > 0) {
                $purchase->delete();
            }
            $invalid_purchase->delete();
            Session::flash('success', 'Invoice bill has been deleted successfully!');
        }
        else {
            Session::flash('error', 'Invoice bill not found!');
        }
        return redirect('/invalid-import-items');
    }
}