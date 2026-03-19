<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Menu;
use App\MenuTitle;
use App\Outlet;
use App\OutletMapper;
use App\Owner;
use App\Printer;
use App\PrinterItemBind;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class PrinterController extends Controller {

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

		$owner_id = Auth::id();

		/*$outlets = [''=>'Select Outlet'];
		$mapper_arr=array();
		$printers = array();

		$mappers = OutletMapper::getOutletIdByOwnerId($owner_id);

		if ( isset($mappers) && sizeof($mappers) > 0 ) {

			foreach($mappers as $mapper)
			{
				$mapper_arr[] = $mapper['outlet_id'];
			}

			$outlets_arr = Outlet::whereIn('id',$mapper_arr)->get();

			foreach($outlets_arr as $ol ) {
				$outlets[$ol->id]=$ol->name;
			}

			if( sizeof($outlets_arr) == 1 ) {
				unset($outlets['']);
			}

			$printers = Printer::join('outlets as ot','ot.id','=','printers.outlet_id')
									->select('printers.*','ot.name as name')
										->whereIn('outlet_id',$mapper_arr)->get();

		}

		return view('printers.index',array('printers'=>$printers,'outlets'=>$outlets));*/

		$printers = Printer::where('created_by',$owner_id)->get();

		return view('printers.index',array('printers'=>$printers));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{

		return view('printers.create',array('action'=>'add'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$owner_id = Auth::id();

		$printer_name = Input::get('printer_name');
		$mac_add = Input::get('mac_address');
		$printer_mfg = Input::get('printer_mfg');
		$printer_ip = Input::get('printer_ip');

		//if ( isset($printer_name) && $printer_name != '' && isset($mac_add) && $mac_add != ''  ) {
		if ( isset($printer_name) && $printer_name != ''  ) {

			$printr_obj = new Printer();
			$printr_obj->printer_name = $printer_name;
            if(isset($printer_mfg) && sizeof($printer_mfg)>0){
                $printr_obj->printer_mfg = $printer_mfg;
            }
            if(isset($printer_ip) && sizeof($printer_ip)>0){
                $printr_obj->printer_ip = Input::get('printer_type')=='network' && $printer_mfg=='epson'?"TCP:".$printer_ip:$printer_ip;
            }
			$printr_obj->printer_type = Input::get('printer_type');
			$printr_obj->mac_address = Input::get('printer_type')=='bluetooth'?trim($mac_add):"TCP:".trim($mac_add);
			$printr_obj->created_by = $owner_id;
			$printr_obj->updated_by = $owner_id;
			$result = $printr_obj->save();

			if ( $result ) {
				return Redirect::route('printers.index')->with('success','New printer has been added successfully.');
			} else {
				return Redirect::route('printers.create')->with('error','Please check input details.');
			}

		} else {
			return redirect()->back()->withInput(Input::all())->with('error','Please fill all the details.');
		}

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$owner_id = Auth::id();
		$printer = Printer::find($id);
		$printer['mac_address'] = str_replace('TCP:','',$printer['mac_address']);
		$printer['printer_ip'] = str_replace('TCP:','',$printer['printer_ip']);
		return view('printers.edit',array('printer'=>$printer,'action'=>'edit'));

	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$pr = Validator::make(Input::all(), [
			'printer_name' => 'required',
			'printer_type' => 'required'
		]);

		if (isset($pr) && $pr->passes())
		{
			$owner_id = Auth::user()->id;

			$mac_add = Input::get('mac_address');
            $printer_mfg = Input::get('printer_mfg');
            $printer_ip = Input::get('printer_ip');

			$printer = Printer::find($id);
			$printer->printer_name = Input::get('printer_name');
            if(isset($printer_mfg) && sizeof($printer_mfg)>0){
                $printer->printer_mfg = $printer_mfg;
            }
            if(isset($printer_ip) && sizeof($printer_ip)>0){
                $printer->printer_ip = Input::get('printer_type')=='network' && $printer_mfg=='epson'?"TCP:".$printer_ip:$printer_ip;
            }
			$printer->printer_type = Input::get('printer_type');
			$printer->mac_address = Input::get('printer_type')=='bluetooth'?trim($mac_add):"TCP:".trim($mac_add);
			$printer->updated_by = $owner_id;
			$result = $printer->save();

			if ( $result ) {
				return Redirect::route('printers.index')->with('success', 'Printer information updated successfully!');
			}

		} else {
			return redirect()->back()->withInput(Input::all())->withErrors($pr->errors());
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
	    $is_printer = Printer::find($id);
        if(isset($is_printer)){
            $printer_item = PrinterItemBind::where('printer_id',$id)->get();
            if(isset($printer_item) && sizeof($printer_item)>0){
                Session::flash('failure', 'Printer bind with some items, Please unbind Printer first, and try again!');
                return Redirect::to('printers');
            }
            $outlet_list = OutletMapper::getOutletMapperByOwnerId(Auth::id());
            foreach ($outlet_list as $outlet){
                $outlet_details = Outlet::find($outlet->id);
                if(isset($outlet_details->printer) && sizeof($outlet_details->printer) > 0) {
                    foreach (json_decode($outlet_details->printer) as $key => $value) {
                        if ($value == $id) {
                            Session::flash('failure', 'Printer bind with outlet, Please unbind Printer first, and try again!');
                            return Redirect::to('printers');
                        }
                    }
                }
            }
        }else{
            Session::flash('failure', 'Printer not found!');
            return Redirect::to('printers');
        }
		Printer::where('id',$id)->delete();
		Session::flash('success', 'Printer detail has been deleted successfully!');
		return Redirect::to('printers');
	}

	public function printerBind(Request $request) {

		$admin_id = Owner::menuOwner();
		$owner_id = Auth::id();

		if ($request->ajax()) {

			$cat_id = Input::get('cat_id');

			//if outlet session is set than load list according session outlet
			$ot_id = Session::get('outlet_session');

			$arr = array();

			$menus = Menu::leftjoin('menu_titles as mt','mt.id', '=','menus.menu_title_id')
				->select('menus.id as id','menus.item as item','mt.title as title','menus.menu_title_id as cat_id')
				->where('menus.created_by',$admin_id)
				->orderBy('menus.menu_title_id','asc')
				->orderBy('menus.id','asc');

			if ( isset($cat_id) && $cat_id != 'all' ) {
				$result = $menus->where('mt.id',$cat_id)->get();
			} else {
				$result = $menus->get();
			}

			if ( isset($result) && sizeof($result) > 0 ) {
				foreach( $result as $res ) {

					$arr[$res->id]['id'] = $res->id;
					$arr[$res->id]['item'] = $res->item;
					$arr[$res->id]['cat_id'] = $res->cat_id;
					$arr[$res->id]['cat_name'] = $res->title;

					//get stock level
					$printer_bind = PrinterItemBind::where('outlet_id',$ot_id)
						->where('item_id',$res->id)
						->first();

					if( isset($printer_bind) && sizeof($printer_bind) > 0 ) {
						$arr[$res->id]['printer'] = $printer_bind->printer_id;
					} else {

						$ot_obj = Outlet::find($ot_id);
						if ( isset($ot_obj->printer) && !is_null($ot_obj->printer) ) {
							$default_kot = json_decode($ot_obj->printer);
							$printer_result = Printer::find($default_kot->kot_printer);
							$arr[$res->id]['printer'] = $printer_result->id;
						} else {
							$arr[$res->id]['printer'] = '';
						}

					}
				}
			}

			$printers = Printer::where('created_by',$admin_id)->get();

			return view('printers.printerBindList',array('printers'=>$printers,'arr'=>$arr,'outlet_id'=>$ot_id,'cat_id'=>$cat_id));

		}

		$categories = MenuTitle::getCategoriesDropdown($admin_id);
		//$categories['all'] = 'All Categories';

		return view('printers.printerBind',array('categories'=>$categories));

	}

	public function storePrinterBind(){

		$owner_id = Auth::id();

		$ot_id = Input::get('outlet_id');
		$cat_id = Input::get('cat_id');
		$item_id = Input::get('item_id');
		$printer_id = Input::get('printer_id');


		if( isset($item_id) && sizeof($item_id) > 0 ) {
			foreach( $item_id as $key=>$val ) {

				if ( $printer_id[$key] == '' ) {
					continue;
				}else if($printer_id[$key] == '0' ){
                    $check_item = PrinterItemBind::where('item_id',$val)->where('outlet_id',$ot_id)->first();

                    if ( isset($check_item) && sizeof($check_item) > 0 ) {

                        $check_item->printer_id = 0;
                        $check_item->mac_address = "";
                        $check_item->category_id = $cat_id[$key];
                        $check_item->updated_by = $owner_id;
                        $check_item->save();

                    } else {

                        $add_item = new PrinterItemBind();
                        $add_item->category_id = $cat_id[$key];
                        $add_item->item_id = $val;
                        $add_item->outlet_id = $ot_id;
                        $add_item->printer_id = 0;
                        $add_item->mac_address = "";
                        $add_item->created_by = $owner_id;
                        $add_item->updated_by = $owner_id;
                        $add_item->save();

                    }
                    continue;
                }

				$pr_check = Printer::find($printer_id[$key]);

				$check_item = PrinterItemBind::where('item_id',$val)->where('outlet_id',$ot_id)->first();

				if ( isset($check_item) && sizeof($check_item) > 0 ) {

					$check_item->printer_id = $printer_id[$key];
					$check_item->mac_address = $pr_check->mac_address;
					$check_item->category_id = $cat_id[$key];
					$check_item->updated_by = $owner_id;
					$check_item->save();

				} else {

					$add_item = new PrinterItemBind();
					$add_item->category_id = $cat_id[$key];
					$add_item->item_id = $val;
					$add_item->outlet_id = $ot_id;
					$add_item->printer_id = $printer_id[$key];
					$add_item->mac_address = $pr_check->mac_address;
					$add_item->created_by = $owner_id;
					$add_item->updated_by = $owner_id;
					$add_item->save();

				}

			}
		}

		return Redirect::to('/printer-bind')->with('success','Printer has been bind successfully');

	}

}
