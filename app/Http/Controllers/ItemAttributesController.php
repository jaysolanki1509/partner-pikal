<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\ItemAttribute;
use App\ItemAttributes;
use App\OutletItemAttributesMapper;
use App\OutletMapper;
use App\Owner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ItemAttributesController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$owner_id = Auth::id();
		$admin_id = Owner::menuOwner();

		$attributes = ItemAttribute::leftjoin('outlet_item_attributes_mapper as oim','oim.attribute_id', '=','item_attributes.id')
			->leftjoin('outlets as o','o.id','=','oim.outlet_id')
			->where('item_attributes.created_by',$admin_id)
			->select('item_attributes.id as id','item_attributes.name as name',DB::raw("group_concat(o.name SEPARATOR ', ') as ot_name"))
			->groupBy('item_attributes.id')
			->orderBy('item_attributes.updated_at','desc')
			->get();

		return view('itemattributes.index', array('attributes' => $attributes));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$owner_id = Auth::id();
		$outlets = OutletMapper::getOutletsByOwnerId();
		unset($outlets['']);

		return view('itemattributes.create',array('outlets'=>$outlets,'action'=>'add'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$messages = [
			'name.required' => 'Name is required!',
		];

		$p = Validator::make($request->all(), [
			'name' => 'required',
		],$messages);


		if (isset($p) && $p->passes())
		{
			$owner_id = Auth::id();
			$save_continue = Input::get('saveContinue');
			$name = Input::get('name');
			$outlet_id  = Input::get('outlet_id');
			$sess_outlet_id = Session::get('outlet_session');

			/*if (isset($sess_outlet_id) && $sess_outlet_id != '') {
				$outlet_id = $sess_outlet_id;
			}*/

			$itm_attr = new ItemAttribute();
			$itm_attr->name = $name;
			$itm_attr->created_by = $owner_id;
			$itm_attr->updated_by = $owner_id;
			$result = $itm_attr->save();

			if ( $result ) {

				if ( isset($outlet_id) && sizeof($outlet_id) > 0 ) {
					foreach( $outlet_id as $ot ) {
						$add = new OutletItemAttributesMapper();
						$add->outlet_id = $ot;
						$add->attribute_id = $itm_attr->id;
						$add->save();
					}
				}
				
				if ( isset($save_continue) && $save_continue == 'true' ) {
					return Redirect::route('item-attributes.create')->with('success','The attribute has been saved');
				} else {
					return Redirect::route('item-attributes.index')->with('success','The attribute has been saved');
				}

			}

		} else {
			return redirect()->back()->withInput(Input::all())->withErrors($p->errors());
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
		$attribute = ItemAttribute::find($id);

		//outlet mapped
		$outlets = OutletMapper::getOutletsByOwnerId();
		unset($outlets['']);

		$ot_mapped = OutletItemAttributesMapper::where('attribute_id',$id)->get();

		return view('itemattributes.edit',array('item_attr'=>$attribute,'outlets'=>$outlets,'ot_mapped'=>$ot_mapped,'action'=>'edit'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{

		$owner_id = Auth::id();
		$save_continue = Input::get('saveContinue');
		$name = Input::get('name');
		$outlet_id  = Input::get('outlet_id');
		$sess_outlet_id = Session::get('outlet_session');

		/*if (isset($sess_outlet_id) && $sess_outlet_id != '') {
			$outlet_id = $sess_outlet_id;
		}*/

		$itm_attr = ItemAttribute::find($id);
		$itm_attr->name = $name;
		$itm_attr->updated_by = $owner_id;
		$itm_attr->updated_at = date('Y-m-d H:i:s');
		$result = $itm_attr->save();

		if ( $result ) {

			OutletItemAttributesMapper::where('attribute_id',$id)->delete();
			if ( isset($outlet_id) && sizeof($outlet_id) > 0 ) {

				foreach( $outlet_id as $ot ) {
					$add = new OutletItemAttributesMapper();
					$add->outlet_id = $ot;
					$add->attribute_id = $id;
					$add->save();
				}
			}

			if ( isset($save_continue) && $save_continue == 'true' ) {
				return Redirect::route('item-attributes.create')->with('success','New Item Attribute has been added....');
			} else {
				return Redirect::route('item-attributes.index')->with('success','New Item Attribute has been updated....');
			}

		}

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy()
	{
		$atr_id = Input::get('att_id');

		$check = ItemAttribute::find($atr_id)->delete();

		if ( $check ) {
			return 'success';
		} else {
			return 'error';
		}

	}

}
