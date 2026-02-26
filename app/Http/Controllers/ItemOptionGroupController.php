<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\ItemGroupOption;
use App\ItemOptionGroup;
use App\ItemOptionGroupMapper;
use App\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class ItemOptionGroupController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

        $outlet_id = Session::get('outlet_session');

        $ot_option_groups = ItemOptionGroup::getOutletOptiongroups($outlet_id);

        return view('itemoptiongroups.index', array('option_groups'=>$ot_option_groups));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $outlet_id = Session::get('outlet_session');

        //customization menu list
        $menu_items = Menu::getOutletMenuItems($outlet_id);
        $max_arr = array(1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9,10=>10);

        return view('itemoptiongroups.create',array('action'=>'add','max_arr'=>$max_arr,'menu_items'=>$menu_items,));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
	    $owner_id = Auth::id();
        $outlet_id = Session::get('outlet_session');

        $save_continue = Request::get('saveContinue');
        $item_option_id = Request::get('option_item_id');
        $item_option_price = Request::get('option_item_price');
        $item_option_default = Request::get('option_item_default');
        $name = Request::get('name');
        $max = Request::get('max');
        $select_type = Request::get('select_type');

        $item_option_group = new ItemOptionGroup();
        $item_option_group->outlet_id = $outlet_id;
        $item_option_group->name = $name;
        $item_option_group->max = $max;
        $item_option_group->created_by = $owner_id;
        $item_option_group->updated_by = $owner_id;
        $result = $item_option_group->save();

        if ( $result ) {

            if ( isset($item_option_id) && sizeof($item_option_id) > 0 ) {

                $item_option_size = sizeof($item_option_id);

                if ( $item_option_size > 0 ) {

                    for ( $cnt=0; $cnt < $item_option_size; $cnt++ ) {

                        $default = 0;

                        if ( $item_option_price[$cnt] == '') {
                            $item_option_price[$cnt] = 0;
                        }

                        if ( isset($item_option_default)) {
                            if ( in_array($item_option_id[$cnt],$item_option_default) ) {
                                $default = 1;
                            }
                        }


                        $add_option = new ItemGroupOption();
                        $add_option->item_option_group_id = $item_option_group->id;
                        $add_option->option_item_id = $item_option_id[$cnt];
                        $add_option->option_item_price = $item_option_price[$cnt];
                        $add_option->default_option = $default;
                        $add_option->created_by = $owner_id;
                        $add_option->updated_by = $owner_id;
                        $add_option->save();
                    }
                }

            }

        } else {
            return Redirect('/item-option-group/create')->withInput(Request::all())->with('error', 'Failed');
        }


        if ( isset($save_continue) && $save_continue == 'true' ) {
            return Redirect::route('itemoptiongroups.create')->with('success','New Item option group has been added....');
        } else {
            return Redirect::route('itemoptiongroups.index')->with('success','New Item option group has been added....');
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

        $outlet_id = Session::get('outlet_session');

        $item_option_group = ItemOptionGroup::with('itemGroupOptions')->find($id);
        $item_option_group['item_option_group_id'] = $id;

        $item_group_options = array();
        if ( $item_option_group->itemGroupOptions && sizeof($item_option_group->itemGroupOptions) > 0 ) {

            $i=0;
            foreach ( $item_option_group->itemGroupOptions as $opt ) {

                $item = Menu::where('id',$opt->option_item_id)->first();

                $item_group_options[$i]['id'] = $opt->option_item_id;
                $item_group_options[$i]['default'] = $opt->default_option;
                $item_group_options[$i]['price'] = $opt->option_item_price;
                $item_group_options[$i]['name'] = $item->item;
                $i++;
            }

        }

        //customization menu list
        $menu_items = Menu::getOutletMenuItems($outlet_id);

        $max_arr = array(1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9,10=>10);

        return view('itemoptiongroups.edit',array(
                                                    'itemoptiongroup'=>$item_option_group,
                                                    'item_group_options'=>$item_group_options,
                                                    'menu_items'=>$menu_items,
                                                    'max_arr'=>$max_arr,
                                                    'action'=>'edit'
                                                ));
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

	    $name = Request::get('name');
        $select_type = Request::get('select_type');
        $max = Request::get('max');
        $item_option_id = Request::get('option_item_id');
        $item_option_price = Request::get('option_item_price');
        $item_option_default = Request::get('option_item_default');

        $item_option_group = ItemOptionGroup::find($id);
        $item_option_group->name = $name;
        $item_option_group->max = $max;
        $item_option_group->updated_by = $owner_id;
        $result = $item_option_group->save();

        if ( $result ) {

            if ( isset($item_option_id) && sizeof($item_option_id) > 0 ) {

                $item_option_size = sizeof($item_option_id);

                if ( $item_option_size > 0 ) {

                    for ( $cnt=0; $cnt < $item_option_size; $cnt++ ) {

                        $default = 0;
                        if ( $item_option_price[$cnt] == '') {
                            $item_option_price[$cnt] = 0;
                        }

                        if ( isset($item_option_default)) {
                            if ( in_array($item_option_id[$cnt],$item_option_default) ) {
                                $default = 1;
                            }
                        }


                        $check_option = ItemGroupOption::where('item_option_group_id',$id)->where('option_item_id',$item_option_id[$cnt])->first();

                        if ( isset($check_option) && sizeof($check_option) > 0 ) {

                            $check_option->option_item_price = $item_option_price[$cnt];
                            $check_option->default_option = $default;
                            $check_option->updated_by = $owner_id;
                            $check_option->save();

                        } else {

                            $add_option = new ItemGroupOption();
                            $add_option->item_option_group_id = $id;
                            $add_option->option_item_id = $item_option_id[$cnt];
                            $add_option->option_item_price = $item_option_price[$cnt];
                            $add_option->default_option = $default;
                            $add_option->created_by = $owner_id;
                            $add_option->updated_by = $owner_id;
                            $add_option->save();

                        }

                    }
                }

            }

        }

        return Redirect('/item-option-groups')->with('success', 'Item option group updated successfully.');

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{

	    //unbind item option group from item
        ItemOptionGroupMapper::where('item_option_group_id',$id)->delete();
        ItemOptionGroup::where('id',$id)->delete();

        Session::flash('success', 'Item option group has been deleted successfully!');
        return Redirect::to('item-option-groups');
	}


    public function removeItemGroupOption() {

        $item_option_group_id = Request::get('item_option_group_id');
        $item_option_id = Request::get('item_option_id');

        $check = ItemGroupOption::where('item_option_group_id',$item_option_group_id)->where('option_item_id',$item_option_id)->delete();

        if ( $check ) {
            return 'success';
        } else {
            return 'error';
        }
    }
}
